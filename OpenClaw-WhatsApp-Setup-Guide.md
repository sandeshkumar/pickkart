# OpenClaw + WhatsApp Setup Guide for PickKart

**Complete guide to set up AI-powered WhatsApp customer support for your PickKart store.**

---

## Table of Contents

1. [Overview](#1-overview)
2. [Prerequisites](#2-prerequisites)
3. [VPS Server Setup](#3-vps-server-setup)
4. [Install OpenClaw](#4-install-openclaw)
5. [Connect WhatsApp](#5-connect-whatsapp)
6. [Configure Access Control](#6-configure-access-control)
7. [Create PickKart Skill](#7-create-pickkart-skill)
8. [Database Query Tools](#8-database-query-tools)
9. [AI Model Configuration](#9-ai-model-configuration)
10. [Test the Bot](#10-test-the-bot)
11. [Production Deployment](#11-production-deployment)
12. [Monitoring & Maintenance](#12-monitoring--maintenance)
13. [Conversation Examples](#13-conversation-examples)
14. [Troubleshooting](#14-troubleshooting)
15. [Cost Summary](#15-cost-summary)

---

## 1. Overview

### What We're Building

An AI-powered WhatsApp assistant that:
- Auto-replies to customer queries 24/7
- Checks product availability from PickKart's database
- Helps customers place orders via WhatsApp
- Sends order status updates
- Handles FAQs (shipping, returns, payment)

### Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                      YOUR VPS SERVER                         │
│                                                              │
│   ┌──────────────┐    ┌──────────────┐    ┌──────────────┐  │
│   │   WhatsApp   │    │   OpenClaw   │    │   PickKart   │  │
│   │   Baileys    │◀──▶│   Gateway    │◀──▶│   MySQL DB   │  │
│   │  (Protocol)  │    │              │    │              │  │
│   └──────────────┘    │  ┌────────┐  │    └──────────────┘  │
│                       │  │ AI LLM │  │                       │
│                       │  │        │  │                       │
│                       │  └────────┘  │                       │
│                       └──────────────┘                       │
└─────────────────────────────────────────────────────────────┘
         ▲
         │ WhatsApp Web Protocol (free)
         ▼
┌──────────────────┐
│  Customer Phone  │
│  (WhatsApp)      │
└──────────────────┘
```

### How Baileys Works (Free WhatsApp Connection)

OpenClaw uses **Baileys** — an open-source library that emulates WhatsApp Web's linked device protocol.

| Feature | Official WhatsApp Business API | OpenClaw + Baileys |
|---------|-------------------------------|-------------------|
| Cost per message | Rs 0.40 - Rs 6 | **Free** |
| Approval required | Yes (Meta/Facebook) | **No** |
| Account type | Business account | **Any personal number** |
| Setup time | Weeks | **5 minutes (QR scan)** |
| Self-hosted | No | **Yes** |
| AI integration | Limited | **Full (any LLM)** |

---

## 2. Prerequisites

### What You Need Before Starting

| Requirement | Details | Cost |
|-------------|---------|------|
| **VPS Server** | Ubuntu 22.04+, 1GB+ RAM, 20GB storage | Rs 500-800/month |
| **Dedicated Phone Number** | New SIM card for WhatsApp bot | Rs 100-200 (one-time) |
| **Domain Name** (optional) | For SSL and webhook | Already have pickkart.co.in |
| **Node.js 20+** | Runtime for OpenClaw | Free |
| **AI API Key** (optional) | Claude, GPT, or use free local model | Free to Rs 1500/month |
| **SSH Access** | Terminal access to your VPS | Included with VPS |

### Recommended VPS Providers for India

| Provider | Plan | RAM | Cost/month | Notes |
|----------|------|-----|------------|-------|
| **DigitalOcean** | Basic Droplet | 1GB | ~Rs 500 | Bangalore datacenter available |
| **Hetzner** | CX22 | 2GB | ~Rs 400 | Best value, EU servers |
| **Hostinger VPS** | KVM 1 | 4GB | ~Rs 500 | Same provider as your hosting |
| **Linode** | Nanode | 1GB | ~Rs 500 | Mumbai datacenter |
| **AWS Lightsail** | Nano | 512MB | ~Rs 300 | Mumbai region |

---

## 3. VPS Server Setup

### Step 3.1: Create a VPS

1. Sign up at your chosen VPS provider (e.g., DigitalOcean)
2. Create a new server:
   - **OS:** Ubuntu 22.04 LTS
   - **RAM:** 1GB minimum (2GB recommended)
   - **Region:** Bangalore / Mumbai (closest to India)
   - **SSH Key:** Add your SSH public key

### Step 3.2: Connect to Your VPS

```bash
ssh root@YOUR_VPS_IP
```

### Step 3.3: Initial Server Setup

```bash
# Update system packages
apt update && apt upgrade -y

# Create a non-root user
adduser pickkart
usermod -aG sudo pickkart

# Switch to new user
su - pickkart
```

### Step 3.4: Install Node.js 20+

```bash
# Install Node.js via nvm (recommended)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash

# Reload shell
source ~/.bashrc

# Install Node.js 20
nvm install 20
nvm use 20

# Verify installation
node --version   # Should show v20.x.x
npm --version    # Should show 10.x.x
```

### Step 3.5: Install MySQL Client (for database queries)

```bash
sudo apt install -y mysql-client
```

### Step 3.6: Test Database Connection

Test connectivity to your Hostinger MySQL database:

```bash
mysql -h YOUR_HOSTINGER_DB_HOST -u YOUR_DB_USER -p YOUR_DB_NAME -e "SELECT COUNT(*) FROM products WHERE status='active';"
```

> **Note:** Your Hostinger database may only allow connections from specific IPs. You'll need to whitelist your VPS IP in Hostinger's **Remote MySQL** settings:
> - Login to hPanel > Databases > Remote MySQL
> - Add your VPS IP address

---

## 4. Install OpenClaw

### Step 4.1: Install OpenClaw CLI

```bash
# Install OpenClaw globally
curl -fsSL https://get.openclaw.ai | bash

# Or via npm
npm install -g @openclaw/cli

# Verify installation
openclaw --version
```

### Step 4.2: Initialize OpenClaw

```bash
# Create workspace
mkdir -p ~/.openclaw/workspace
cd ~/.openclaw

# Initialize configuration
openclaw init
```

This creates the default directory structure:

```
~/.openclaw/
├── config.json5          # Main configuration
├── credentials/          # Channel credentials (auto-generated)
│   └── whatsapp/
├── workspace/
│   └── skills/           # Custom skills/plugins
│       └── pickkart/     # Our PickKart skill
└── logs/                 # Gateway logs
```

---

## 5. Connect WhatsApp

### Step 5.1: Get a Dedicated Phone Number

**Important:** Use a **separate SIM card** for the WhatsApp bot. Do NOT use your personal number.

Why:
- If WhatsApp flags the bot, your personal account stays safe
- Cleaner separation between personal and business messages
- Easier to manage

**Options:**
- Buy a new prepaid SIM (Jio/Airtel/Vi) — Rs 100-200
- Use a virtual number service
- Use a dual-SIM phone temporarily for setup

### Step 5.2: Activate WhatsApp on the New Number

1. Install WhatsApp on a phone with the new SIM
2. Complete phone verification
3. Set up a profile (name: "PickKart Support", photo: your logo)

### Step 5.3: Link to OpenClaw

On your VPS, run:

```bash
openclaw channels login --channel whatsapp
```

This displays a **QR code** in your terminal.

On the phone with WhatsApp:
1. Open WhatsApp
2. Go to **Settings > Linked Devices > Link a Device**
3. Scan the QR code shown in your terminal

You should see:

```
[INFO] WhatsApp linked successfully!
[INFO] Account: +91XXXXXXXXXX
[INFO] Session stored at: ~/.openclaw/credentials/whatsapp/default/creds.json
```

### Step 5.4: Verify Connection

```bash
openclaw channels status
```

Expected output:

```
Channel    Account     Status
─────────  ──────────  ────────
whatsapp   default     connected
```

---

## 6. Configure Access Control

### Step 6.1: Edit Configuration

```bash
nano ~/.openclaw/config.json5
```

### Step 6.2: WhatsApp Channel Configuration

```json5
{
  // Gateway settings
  gateway: {
    host: "127.0.0.1",
    port: 18789,
  },

  // Channel configurations
  channels: {
    whatsapp: {
      // === ACCESS CONTROL ===

      // Who can send DMs to the bot
      // Options: "open", "pairing", "allowlist", "disabled"
      dmPolicy: "open",
      allowFrom: ["*"],    // Accept from all numbers

      // For restricted access (e.g., testing phase):
      // dmPolicy: "allowlist",
      // allowFrom: ["+919876543210", "+919876543211"],

      // === GROUP SETTINGS ===

      // Disable group chats (not needed for customer support)
      groupPolicy: "disabled",

      // === MESSAGE SETTINGS ===

      // Read receipts (blue ticks)
      sendReadReceipts: true,

      // Acknowledge messages with emoji while processing
      ackReaction: {
        emoji: "👀",      // Shows 👀 while AI is thinking
        direct: true,
        group: false,
      },

      // Message chunking (for long replies)
      chunking: {
        mode: "newline",   // Split at paragraph breaks
        limit: 4000,       // Max characters per message
      },

      // === MEDIA SETTINGS ===

      // Accept images from customers (e.g., screenshots of issues)
      mediaMaxMb: 10,

      // === RATE LIMITING ===

      // Prevent spam/abuse
      rateLimit: {
        maxMessages: 30,       // Max 30 messages per window
        windowSeconds: 60,     // Per 60 seconds
      },
    },
  },
}
```

### Step 6.3: Save and Validate

```bash
# Validate config
openclaw config validate

# Expected output:
# Configuration is valid!
```

---

## 7. Create PickKart Skill

The "skill" is the AI brain that knows how to handle PickKart-related queries.

### Step 7.1: Create Skill Directory

```bash
mkdir -p ~/.openclaw/workspace/skills/pickkart/tools
```

### Step 7.2: Create the Skill Definition

```bash
nano ~/.openclaw/workspace/skills/pickkart/SKILL.md
```

Write the following:

```markdown
# PickKart Customer Support

You are PickKart's friendly WhatsApp customer support assistant.
Website: https://pickkart.co.in

## Your Personality
- Friendly, helpful, and professional
- Reply in the same language the customer uses (Hindi or English)
- Keep messages concise (WhatsApp-friendly, not essay-length)
- Use emojis sparingly but naturally
- Always show prices in INR (Rs or ₹)

## What You Can Do

### Product Queries
- Search products by name, category, or brand
- Check stock availability
- Show product prices and discounts
- Compare products
- Recommend products based on customer needs

### Order Management
- Help customers place orders (collect name, address, phone, product details)
- Check order status by order number
- Explain shipping timelines
- Process return/exchange requests (escalate to human if complex)

### Store Information
- Store hours: Online 24/7
- Shipping: Free on orders over Rs 499
- Returns: 30-day hassle-free returns
- Payment: COD, UPI, Cards accepted
- Contact email: support@pickkart.co.in

## Response Format Guidelines
- For product listings, use this format:
  📦 *Product Name*
  💰 Rs X,XXX (~~Rs X,XXX~~)
  ✅ In Stock / ❌ Out of Stock

- For order confirmations:
  🎉 *Order Confirmed!*
  Order #: ORD-XXXX
  Item: Product Name
  Total: Rs X,XXX

- For errors or unavailable items:
  😔 Sorry, [item] is currently unavailable.
  Would you like me to suggest alternatives?

## Escalation Rules
- If customer is angry or issue is complex → say "Let me connect you with our team"
  and provide the support email
- Never make up information about products not in the database
- Never promise specific delivery dates unless confirmed
- Never share other customers' information

## Available Tools
- check_product: Search products in the store
- check_order: Look up order status
- check_variants: Get product variants (sizes, colors)
- create_order: Place a new order
- list_categories: Show available product categories
```

---

## 8. Database Query Tools

These are the tools that let OpenClaw query your PickKart database.

### Step 8.1: Install MySQL Package

```bash
cd ~/.openclaw/workspace/skills/pickkart
npm init -y
npm install mysql2
```

### Step 8.2: Create Database Connection Helper

```bash
nano ~/.openclaw/workspace/skills/pickkart/tools/db.js
```

```javascript
const mysql = require('mysql2/promise');

// PickKart Database Connection
// Update these with your Hostinger MySQL credentials
const DB_CONFIG = {
  host: 'YOUR_HOSTINGER_DB_HOST',      // e.g., srv1234.hstgr.io
  port: 3306,
  user: 'YOUR_DB_USERNAME',             // e.g., u282948928_admin
  password: 'YOUR_DB_PASSWORD',
  database: 'u282948928_zedkart',
  waitForConnections: true,
  connectionLimit: 5,
  queueLimit: 0,
};

let pool = null;

function getPool() {
  if (!pool) {
    pool = mysql.createPool(DB_CONFIG);
  }
  return pool;
}

async function query(sql, params = []) {
  const connection = await getPool().getConnection();
  try {
    const [rows] = await connection.execute(sql, params);
    return rows;
  } finally {
    connection.release();
  }
}

module.exports = { query };
```

### Step 8.3: Create Product Search Tool

```bash
nano ~/.openclaw/workspace/skills/pickkart/tools/check_product.js
```

```javascript
const { query } = require('./db');

module.exports = {
  name: 'check_product',
  description: 'Search for products in the PickKart store by name, category, or brand',
  parameters: {
    type: 'object',
    properties: {
      search: {
        type: 'string',
        description: 'Product name, keyword, category, or brand to search for',
      },
    },
    required: ['search'],
  },

  async execute({ search }) {
    const results = await query(
      `SELECT
        p.id,
        p.name,
        p.price,
        p.compare_at_price,
        p.stock_quantity,
        p.short_description,
        p.slug,
        c.name AS category,
        b.name AS brand
      FROM products p
      LEFT JOIN categories c ON p.category_id = c.id
      LEFT JOIN brands b ON p.brand_id = b.id
      WHERE p.status = 'active'
        AND p.stock_quantity > 0
        AND (
          p.name LIKE ?
          OR p.short_description LIKE ?
          OR c.name LIKE ?
          OR b.name LIKE ?
        )
      ORDER BY p.is_featured DESC, p.created_at DESC
      LIMIT 5`,
      [`%${search}%`, `%${search}%`, `%${search}%`, `%${search}%`]
    );

    if (results.length === 0) {
      return JSON.stringify({
        found: false,
        message: `No products found matching "${search}".`,
      });
    }

    return JSON.stringify({
      found: true,
      count: results.length,
      products: results.map((p) => ({
        id: p.id,
        name: p.name,
        brand: p.brand || 'Unbranded',
        category: p.category,
        price: `Rs ${Number(p.price).toLocaleString('en-IN')}`,
        originalPrice: p.compare_at_price
          ? `Rs ${Number(p.compare_at_price).toLocaleString('en-IN')}`
          : null,
        discount: p.compare_at_price
          ? `${Math.round(((p.compare_at_price - p.price) / p.compare_at_price) * 100)}% OFF`
          : null,
        inStock: p.stock_quantity > 0,
        stockQty: p.stock_quantity,
        url: `https://pickkart.co.in/products/${p.slug}`,
        description: p.short_description || '',
      })),
    });
  },
};
```

### Step 8.4: Create Variants Check Tool

```bash
nano ~/.openclaw/workspace/skills/pickkart/tools/check_variants.js
```

```javascript
const { query } = require('./db');

module.exports = {
  name: 'check_variants',
  description: 'Get available variants (sizes, colors, etc.) for a specific product',
  parameters: {
    type: 'object',
    properties: {
      product_id: {
        type: 'number',
        description: 'The product ID to check variants for',
      },
    },
    required: ['product_id'],
  },

  async execute({ product_id }) {
    const variants = await query(
      `SELECT
        id, name, sku, option1, option2, option3,
        price, compare_at_price, stock_quantity, is_active
      FROM product_variants
      WHERE product_id = ? AND is_active = 1
      ORDER BY sort_order ASC`,
      [product_id]
    );

    const product = await query(
      `SELECT name, options FROM products WHERE id = ?`,
      [product_id]
    );

    if (variants.length === 0) {
      return JSON.stringify({
        hasVariants: false,
        message: 'This product has no variants (single option only).',
      });
    }

    // Parse option names from product JSON
    let optionNames = [];
    try {
      const opts = JSON.parse(product[0]?.options || '[]');
      optionNames = opts.map((o) => o.name);
    } catch (e) {
      optionNames = ['Option 1', 'Option 2', 'Option 3'];
    }

    return JSON.stringify({
      hasVariants: true,
      productName: product[0]?.name,
      optionNames: optionNames,
      variants: variants.map((v) => ({
        id: v.id,
        name: v.name,
        options: [v.option1, v.option2, v.option3].filter(Boolean),
        price: v.price ? `Rs ${Number(v.price).toLocaleString('en-IN')}` : 'Same as base',
        inStock: v.stock_quantity > 0,
        stockQty: v.stock_quantity,
      })),
    });
  },
};
```

### Step 8.5: Create Order Status Tool

```bash
nano ~/.openclaw/workspace/skills/pickkart/tools/check_order.js
```

```javascript
const { query } = require('./db');

module.exports = {
  name: 'check_order',
  description: 'Look up order status by order number or customer phone number',
  parameters: {
    type: 'object',
    properties: {
      order_number: {
        type: 'string',
        description: 'Order number (e.g., ORD-2026-0001) or customer phone number',
      },
    },
    required: ['order_number'],
  },

  async execute({ order_number }) {
    let orders;

    // Check if it's a phone number or order number
    if (order_number.match(/^\+?\d{10,13}$/)) {
      // Search by phone number - get latest 3 orders
      orders = await query(
        `SELECT o.*, u.name as customer_name
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.phone = ? OR o.phone = ?
        ORDER BY o.created_at DESC
        LIMIT 3`,
        [order_number, order_number.replace('+91', '')]
      );
    } else {
      // Search by order number
      orders = await query(
        `SELECT o.*, u.name as customer_name
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.order_number = ?
        LIMIT 1`,
        [order_number]
      );
    }

    if (orders.length === 0) {
      return JSON.stringify({
        found: false,
        message: `No orders found for "${order_number}".`,
      });
    }

    // Get order items for each order
    const ordersWithItems = await Promise.all(
      orders.map(async (order) => {
        const items = await query(
          `SELECT oi.*, p.name as product_name
          FROM order_items oi
          LEFT JOIN products p ON oi.product_id = p.id
          WHERE oi.order_id = ?`,
          [order.id]
        );

        return {
          orderNumber: order.order_number,
          status: order.status,
          paymentStatus: order.payment_status,
          total: `Rs ${Number(order.total).toLocaleString('en-IN')}`,
          date: new Date(order.created_at).toLocaleDateString('en-IN'),
          items: items.map((i) => ({
            product: i.product_name,
            quantity: i.quantity,
            price: `Rs ${Number(i.price).toLocaleString('en-IN')}`,
          })),
        };
      })
    );

    return JSON.stringify({
      found: true,
      orders: ordersWithItems,
    });
  },
};
```

### Step 8.6: Create Category Listing Tool

```bash
nano ~/.openclaw/workspace/skills/pickkart/tools/list_categories.js
```

```javascript
const { query } = require('./db');

module.exports = {
  name: 'list_categories',
  description: 'List all active product categories with product counts',
  parameters: {
    type: 'object',
    properties: {},
  },

  async execute() {
    const categories = await query(
      `SELECT
        c.name,
        c.slug,
        COUNT(p.id) as product_count
      FROM categories c
      LEFT JOIN products p ON p.category_id = c.id AND p.status = 'active'
      WHERE c.is_active = 1
      GROUP BY c.id, c.name, c.slug
      HAVING product_count > 0
      ORDER BY c.sort_order ASC, c.name ASC`
    );

    return JSON.stringify({
      categories: categories.map((c) => ({
        name: c.name,
        products: c.product_count,
        url: `https://pickkart.co.in/products?category=${c.slug}`,
      })),
    });
  },
};
```

### Step 8.7: Create Order Placement Tool

```bash
nano ~/.openclaw/workspace/skills/pickkart/tools/create_order.js
```

```javascript
const { query } = require('./db');

module.exports = {
  name: 'create_order',
  description: 'Place a new order for a customer. Collect all required details before calling this.',
  parameters: {
    type: 'object',
    properties: {
      customer_name: { type: 'string', description: 'Customer full name' },
      customer_phone: { type: 'string', description: 'Customer phone number with +91' },
      customer_email: { type: 'string', description: 'Customer email (optional)' },
      address: { type: 'string', description: 'Full delivery address' },
      city: { type: 'string', description: 'City' },
      state: { type: 'string', description: 'State' },
      pincode: { type: 'string', description: '6-digit PIN code' },
      product_id: { type: 'number', description: 'Product ID to order' },
      variant_id: { type: 'number', description: 'Variant ID (optional)' },
      quantity: { type: 'number', description: 'Quantity to order (default 1)' },
      payment_method: { type: 'string', description: 'cod or online' },
    },
    required: ['customer_name', 'customer_phone', 'address', 'city', 'state', 'pincode', 'product_id'],
  },

  async execute(params) {
    const {
      customer_name, customer_phone, customer_email,
      address, city, state, pincode,
      product_id, variant_id, quantity = 1, payment_method = 'cod'
    } = params;

    // Get product details
    const [product] = await query(
      'SELECT id, name, price, stock_quantity FROM products WHERE id = ? AND status = ?',
      [product_id, 'active']
    );

    if (!product) {
      return JSON.stringify({ success: false, message: 'Product not found or unavailable.' });
    }

    if (product.stock_quantity < quantity) {
      return JSON.stringify({ success: false, message: `Only ${product.stock_quantity} units available.` });
    }

    // Get variant price if applicable
    let unitPrice = product.price;
    let variantName = '';
    if (variant_id) {
      const [variant] = await query(
        'SELECT price, name FROM product_variants WHERE id = ? AND is_active = 1',
        [variant_id]
      );
      if (variant) {
        unitPrice = variant.price || product.price;
        variantName = variant.name;
      }
    }

    const subtotal = unitPrice * quantity;
    const shipping = subtotal >= 499 ? 0 : 49;
    const total = subtotal + shipping;

    // Generate order number
    const date = new Date();
    const orderNum = `ORD-${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}${String(date.getDate()).padStart(2, '0')}-${Math.random().toString(36).substring(2, 6).toUpperCase()}`;

    // Create the order
    const orderResult = await query(
      `INSERT INTO orders (order_number, status, payment_status, payment_method,
        subtotal, shipping_cost, total, notes,
        shipping_name, shipping_phone, shipping_email,
        shipping_address, shipping_city, shipping_state, shipping_zip,
        created_at, updated_at)
      VALUES (?, 'pending', 'pending', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())`,
      [orderNum, payment_method, subtotal, shipping, total,
        `WhatsApp order from ${customer_phone}`,
        customer_name, customer_phone, customer_email || '',
        address, city, state, pincode]
    );

    // Create order item
    await query(
      `INSERT INTO order_items (order_id, product_id, product_variant_id, product_name,
        quantity, price, total, created_at, updated_at)
      VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())`,
      [orderResult.insertId, product_id, variant_id || null,
        product.name + (variantName ? ` (${variantName})` : ''),
        quantity, unitPrice, subtotal]
    );

    // Update stock
    await query(
      'UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?',
      [quantity, product_id]
    );

    return JSON.stringify({
      success: true,
      order: {
        orderNumber: orderNum,
        product: product.name + (variantName ? ` (${variantName})` : ''),
        quantity: quantity,
        subtotal: `Rs ${subtotal.toLocaleString('en-IN')}`,
        shipping: shipping === 0 ? 'FREE' : `Rs ${shipping}`,
        total: `Rs ${total.toLocaleString('en-IN')}`,
        paymentMethod: payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment',
        deliveryAddress: `${address}, ${city}, ${state} - ${pincode}`,
      },
    });
  },
};
```

---

## 9. AI Model Configuration

OpenClaw needs an AI model to understand customer messages and generate responses.

### Option A: Free Local Model (Ollama)

Best for: Testing, low-volume stores (saves money)

```bash
# Install Ollama on VPS
curl -fsSL https://ollama.ai/install.sh | sh

# Download a model (choose one)
ollama pull llama3.1:8b      # Good balance (4.7GB)
ollama pull mistral:7b        # Fast and capable (4.1GB)
ollama pull phi3:mini          # Lightweight (2.3GB)
```

Configure in `~/.openclaw/config.json5`:

```json5
{
  ai: {
    provider: "ollama",
    model: "llama3.1:8b",
    baseUrl: "http://127.0.0.1:11434",
    temperature: 0.7,
    maxTokens: 1024,
  },
}
```

### Option B: Claude API (Recommended for Quality)

Best for: Production use, better understanding of complex queries

```json5
{
  ai: {
    provider: "anthropic",
    model: "claude-sonnet-4-5-20250929",
    apiKey: "sk-ant-api03-YOUR_KEY_HERE",
    temperature: 0.7,
    maxTokens: 1024,
  },
}
```

**Cost:** ~Rs 200-500/month for moderate usage

### Option C: OpenAI GPT

```json5
{
  ai: {
    provider: "openai",
    model: "gpt-4o-mini",
    apiKey: "sk-YOUR_KEY_HERE",
    temperature: 0.7,
    maxTokens: 1024,
  },
}
```

**Cost:** ~Rs 100-300/month for moderate usage (gpt-4o-mini is very affordable)

---

## 10. Test the Bot

### Step 10.1: Start the Gateway

```bash
openclaw gateway
```

You should see:

```
[INFO] Gateway started on ws://127.0.0.1:18789
[INFO] WhatsApp: connected (+91XXXXXXXXXX)
[INFO] Skills loaded: pickkart (5 tools)
[INFO] AI provider: ollama (llama3.1:8b)
[INFO] Ready for messages!
```

### Step 10.2: Send Test Messages

From **your personal phone** (different from the bot number), open WhatsApp and message the bot number:

**Test 1: Product Search**
```
You: Do you have Nike shoes?
Bot: Let me check...

📦 *Nike Air Max 270*
💰 Rs 12,999 (~~Rs 15,999~~) — 19% OFF
✅ In Stock (15 units)
🔗 https://pickkart.co.in/products/nike-air-max-270

📦 *Nike Revolution 6*
💰 Rs 4,999
✅ In Stock (25 units)

Would you like to know about sizes and colors? Reply with the product name!
```

**Test 2: Check Variants**
```
You: What sizes are available for Nike Air Max?
Bot: Nike Air Max 270 comes in:

🎨 Colors: Black/White, All Black, Blue/Gray
📏 Sizes: UK 7, 8, 9, 10, 11

All variants are priced at Rs 12,999.
Which size and color would you like?
```

**Test 3: Place Order**
```
You: I want to order Nike Air Max, size 9, Black/White
Bot: Great choice! To place your order, I need a few details:

1. Your full name?
2. Delivery address?
3. City, State, PIN code?
4. Payment: COD or Online?
```

**Test 4: Order Status**
```
You: What's the status of order ORD-2026-0216-ABC1?
Bot: Here's your order status:

📋 *Order #ORD-2026-0216-ABC1*
📅 Date: 16/02/2026
📦 Status: Processing
💳 Payment: Pending (COD)

Items:
• Nike Air Max 270 (UK 9, Black/White) x1 — Rs 12,999
📍 Delivery to: Mumbai, MH - 400001

Total: Rs 12,999 (Free Shipping!)
```

### Step 10.3: Test Edge Cases

- Send "Hi" → Should get a welcome message
- Send gibberish → Should ask to rephrase
- Ask about a product that doesn't exist → Should say not found
- Send an image → Should acknowledge but explain it can help with text queries
- Ask in Hindi → Should reply in Hindi

---

## 11. Production Deployment

### Step 11.1: Run as Background Service

Create a systemd service so OpenClaw runs 24/7:

```bash
sudo nano /etc/systemd/system/openclaw.service
```

```ini
[Unit]
Description=OpenClaw WhatsApp Gateway
After=network.target

[Service]
Type=simple
User=pickkart
WorkingDirectory=/home/pickkart/.openclaw
ExecStart=/home/pickkart/.nvm/versions/node/v20.x.x/bin/openclaw gateway
Restart=always
RestartSec=10
StandardOutput=journal
StandardError=journal

# Environment
Environment=NODE_ENV=production

[Install]
WantedBy=multi-user.target
```

> **Note:** Replace `v20.x.x` with your actual Node.js version path. Find it with: `which openclaw`

```bash
# Enable and start the service
sudo systemctl daemon-reload
sudo systemctl enable openclaw
sudo systemctl start openclaw

# Check status
sudo systemctl status openclaw

# View logs
sudo journalctl -u openclaw -f
```

### Step 11.2: Auto-Restart on Crash

The `Restart=always` in the service file handles this. OpenClaw will automatically restart if it crashes.

### Step 11.3: Log Rotation

```bash
sudo nano /etc/logrotate.d/openclaw
```

```
/home/pickkart/.openclaw/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0640 pickkart pickkart
}
```

---

## 12. Monitoring & Maintenance

### Daily Monitoring Commands

```bash
# Check if OpenClaw is running
sudo systemctl status openclaw

# View recent logs
sudo journalctl -u openclaw --since "1 hour ago"

# Check WhatsApp connection
openclaw channels status

# Check system resources
htop
```

### Weekly Maintenance

```bash
# Update OpenClaw
npm update -g @openclaw/cli

# Restart service after update
sudo systemctl restart openclaw

# Check database connectivity
openclaw doctor
```

### WhatsApp Session Management

WhatsApp linked device sessions can disconnect. If this happens:

```bash
# Check status
openclaw channels status

# Re-link if disconnected
openclaw channels login --channel whatsapp

# Restart gateway
sudo systemctl restart openclaw
```

### Backup Credentials

```bash
# Backup WhatsApp session
cp -r ~/.openclaw/credentials ~/.openclaw/credentials.backup.$(date +%Y%m%d)
```

---

## 13. Conversation Examples

### Example 1: Product Discovery

```
Customer: Hi
Bot: Hello! Welcome to PickKart 👋
     I'm your shopping assistant. I can help you with:

     🔍 Search products
     📦 Track your order
     🛒 Place an order
     ❓ Answer questions

     What are you looking for today?

Customer: Show me laptops under 50000
Bot: Here are laptops under Rs 50,000:

     📦 *HP Pavilion 15*
     💻 Intel i5, 8GB RAM, 512GB SSD
     💰 Rs 45,999 (~~Rs 52,999~~) — 13% OFF
     ✅ In Stock

     📦 *Lenovo IdeaPad 3*
     💻 AMD Ryzen 5, 8GB RAM, 256GB SSD
     💰 Rs 38,499
     ✅ In Stock

     Want more details on any of these?

Customer: Tell me more about HP Pavilion
Bot: *HP Pavilion 15* — Full Details

     💻 Specs:
     • Processor: Intel Core i5-1235U
     • RAM: 8GB DDR4
     • Storage: 512GB SSD
     • Display: 15.6" Full HD
     • OS: Windows 11 Home

     💰 Price: Rs 45,999 (Save Rs 7,000!)
     🚚 Free Delivery
     🔄 30-Day Returns

     🔗 View on website: https://pickkart.co.in/products/hp-pavilion-15

     Would you like to order this? I can help!
```

### Example 2: Order via WhatsApp (Hindi)

```
Customer: Bhai Nike shoes chahiye size 9 me
Bot: Zaroor! 👟 Yeh Nike shoes available hain size 9 me:

     📦 *Nike Air Max 270*
     💰 Rs 12,999 (~~Rs 15,999~~)
     ✅ Stock me hai — Size 9

     📦 *Nike Revolution 6*
     💰 Rs 4,999
     ✅ Stock me hai — Size 9

     Kaunsa lena hai? Number bolo!

Customer: 1
Bot: Nike Air Max 270 — Rs 12,999 👍

     Order ke liye mujhe batao:
     1. Aapka naam
     2. Delivery address (full)
     3. City, State, PIN code
     4. Payment: COD ya Online?

Customer: Sandesh Kumar, 123 MG Road, Vashi, Navi Mumbai, Maharashtra 400703, COD
Bot: 🎉 *Order Confirmed!*

     📋 Order #ORD-2026-0216-X7KM
     📦 Nike Air Max 270 (Size 9)
     💰 Rs 12,999 (Free Shipping!)
     💳 Cash on Delivery
     📍 123 MG Road, Vashi, Navi Mumbai, MH - 400703

     📅 Expected delivery: 3-5 business days

     Order status check karne ke liye kabhi bhi message karo:
     "Order status ORD-2026-0216-X7KM"

     Thank you for shopping with PickKart! 🛍️
```

### Example 3: Order Tracking

```
Customer: Where is my order ORD-2026-0210-AB12?
Bot: Let me check... 📋

     *Order #ORD-2026-0210-AB12*
     📅 Ordered: 10 Feb 2026
     📦 Status: *Shipped* 🚚
     💳 Payment: Paid (UPI)

     Items:
     • Samsung Galaxy Buds x1 — Rs 8,999

     Total: Rs 8,999

     Your order is on its way! You should receive it within 1-2 days.

     Need anything else?
```

---

## 14. Troubleshooting

### Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|---------|
| QR code expired | Took too long to scan | Run `openclaw channels login --channel whatsapp` again |
| WhatsApp disconnected | Session expired / phone offline | Re-scan QR code, keep phone connected to internet |
| Bot not responding | Gateway crashed | `sudo systemctl restart openclaw` |
| "Account banned" | Too many messages too fast | Use a new number, slow down message rate |
| DB connection error | Hostinger blocking VPS IP | Whitelist VPS IP in hPanel > Remote MySQL |
| Slow responses | AI model is slow | Use a faster model or upgrade VPS RAM |
| No skill loaded | Skill file has syntax error | Check `openclaw doctor` for errors |
| Messages not arriving | Phone number not linked | Run `openclaw channels status` to verify |

### Diagnostic Commands

```bash
# Full system health check
openclaw doctor

# Test database connection manually
node -e "
const mysql = require('mysql2/promise');
(async () => {
  const conn = await mysql.createConnection({
    host: 'YOUR_HOST', user: 'YOUR_USER',
    password: 'YOUR_PASS', database: 'YOUR_DB'
  });
  const [rows] = await conn.execute('SELECT COUNT(*) as cnt FROM products');
  console.log('Products:', rows[0].cnt);
  await conn.end();
})().catch(console.error);
"

# Check WhatsApp session health
openclaw channels status --verbose

# View real-time logs
sudo journalctl -u openclaw -f --no-pager
```

### Rate Limiting Best Practices

To avoid WhatsApp flagging your account:

- Don't send more than **1 message per second**
- Don't send bulk messages to people who haven't messaged you first
- Keep responses natural (not template-like)
- Don't send the same message to multiple people
- Use a profile picture and proper business name
- Start slowly (10-20 conversations/day) and gradually increase

---

## 15. Cost Summary

### Monthly Operating Costs

| Item | Free Option | Recommended |
|------|-------------|-------------|
| **VPS Server** | — | Rs 500/month (DigitalOcean) |
| **AI Model** | Ollama (local, free) | Claude API: Rs 200-500/month |
| **WhatsApp** | Free (Baileys) | Free |
| **Phone Number** | Rs 200 SIM (one-time) | Rs 200 SIM (one-time) |
| **OpenClaw** | Free (open-source) | Free |
| **Domain/SSL** | Already have | Already have |
| **Total** | ~Rs 500/month | ~Rs 700-1000/month |

### Cost vs Return

If each WhatsApp order averages Rs 2,000 and you get just **1 extra order per day** through WhatsApp:
- Monthly revenue increase: Rs 60,000
- Monthly cost: Rs 700-1000
- **ROI: 60x return**

---

## Quick Start Checklist

```
[ ] 1. Buy a VPS (DigitalOcean/Hetzner)
[ ] 2. Buy a new SIM card for the bot
[ ] 3. Set up WhatsApp on the new number
[ ] 4. SSH into VPS and install Node.js
[ ] 5. Install OpenClaw
[ ] 6. Configure WhatsApp channel
[ ] 7. Scan QR code to link WhatsApp
[ ] 8. Create PickKart skill + tools
[ ] 9. Update database credentials in db.js
[ ] 10. Whitelist VPS IP in Hostinger Remote MySQL
[ ] 11. Choose AI model (Ollama or Claude API)
[ ] 12. Start gateway and test with personal phone
[ ] 13. Set up systemd service for 24/7 operation
[ ] 14. Share the WhatsApp number on your website
[ ] 15. Monitor logs daily for the first week
```

---

*OpenClaw + WhatsApp Setup Guide for PickKart — Last updated: February 2026*
