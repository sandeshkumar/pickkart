# PickKart Admin Guide

**Complete guide for managing your PickKart e-commerce store.**

---

## Table of Contents

1. [Getting Started](#1-getting-started)
2. [Managing Categories](#2-managing-categories)
3. [Managing Brands](#3-managing-brands)
4. [Managing Products](#4-managing-products)
5. [Product Variants](#5-product-variants)
6. [Product Images](#6-product-images)
7. [Managing Orders](#7-managing-orders)
8. [Managing Coupons](#8-managing-coupons)
9. [Managing Banners](#9-managing-banners)
10. [Managing Pages](#10-managing-pages)
11. [Managing Reviews](#11-managing-reviews)
12. [Managing Users & Roles](#12-managing-users--roles)
13. [SEO Settings](#13-seo-settings)
14. [Recommended Setup Order](#14-recommended-setup-order)

---

## 1. Getting Started

### Accessing the Admin Panel

- **URL:** `https://yourdomain.com/admin`
- Login with your admin email and password.
- Only users with **super-admin** or **admin** roles can access the panel.

### Dashboard Overview

After logging in, you'll see the admin dashboard with navigation on the left sidebar:
- **Products** — Manage your product catalog
- **Categories** — Organize products into categories
- **Brands** — Manage product brands
- **Orders** — View and manage customer orders
- **Users** — Manage customers, sellers, and admins
- **Coupons** — Create discount coupons
- **Banners** — Manage homepage banners
- **Pages** — Create static pages (About Us, Privacy Policy, etc.)
- **Reviews** — Moderate customer reviews

---

## 2. Managing Categories

Categories help organize your products. PickKart supports **hierarchical categories** (parent and subcategories).

### Creating a Category

**Navigate to:** Admin > Categories > **New Category**

| Field | Required | Description |
|-------|----------|-------------|
| **Name** | Yes | Category name (e.g., "Electronics"). Slug is auto-generated. |
| **Slug** | Yes | URL-friendly name. Auto-fills from Name. |
| **Parent Category** | No | Leave empty for top-level category. Select a parent for subcategories. |
| **Description** | No | Brief description of the category (max 1000 characters). |
| **Icon** | No | Icon identifier (e.g., `heroicon-o-cpu-chip`). |
| **Image** | No | Upload a category image. |
| **Is Active** | — | Toggle ON to make visible on storefront. Default: ON. |
| **Sort Order** | — | Controls display order. Lower numbers appear first. Default: 0. |

**SEO Fields (collapsible section):**

| Field | Description |
|-------|-------------|
| **Meta Title** | Page title for search engines (recommended: 50–60 characters). |
| **Meta Description** | Page description for search engines (recommended: 150–160 characters). |

### Category Hierarchy Example

```
Electronics              (Parent — Sort Order: 1)
  ├── Smartphones        (Child of Electronics — Sort Order: 1)
  ├── Laptops            (Child of Electronics — Sort Order: 2)
  ├── Tablets            (Child of Electronics — Sort Order: 3)
  └── Accessories        (Child of Electronics — Sort Order: 4)

Clothing                 (Parent — Sort Order: 2)
  ├── Men's Wear         (Child of Clothing)
  ├── Women's Wear       (Child of Clothing)
  └── Kids' Wear         (Child of Clothing)
```

> **Tip:** Always create **parent categories first**, then create subcategories by selecting the parent.

### Editing & Deleting Categories

- Click on any category name in the list to **edit** it.
- Use the **delete** button to remove a category.
- You can **filter** categories by active status or parent category using the filter icon.

---

## 3. Managing Brands

Brands represent product manufacturers or labels.

### Creating a Brand

**Navigate to:** Admin > Brands > **New Brand**

| Field | Required | Description |
|-------|----------|-------------|
| **Name** | Yes | Brand name (e.g., "Samsung"). Slug is auto-generated. |
| **Slug** | Yes | URL-friendly name. Auto-fills from Name. |
| **Description** | No | Brief description of the brand (max 1000 characters). |
| **Logo** | No | Upload the brand's logo image. |
| **Website** | No | Brand's official website URL. |
| **Is Active** | — | Toggle ON to make visible. Default: ON. |

### Example Brands

| Name | Description | Website |
|------|-------------|---------|
| Samsung | Leading electronics manufacturer | https://samsung.com |
| Nike | Global sportswear brand | https://nike.com |
| IKEA | Furniture and home goods | https://ikea.com |
| Bosch | Power tools and appliances | https://bosch.com |

---

## 4. Managing Products

Products are the core of your store. Each product belongs to a **category** and optionally to a **brand**.

### Creating a Product

**Navigate to:** Admin > Products > **New Product**

The product form is organized into **6 tabs**:

---

### Tab 1: Basic Information

| Field | Required | Description |
|-------|----------|-------------|
| **Name** | Yes | Product name (e.g., "Samsung Galaxy S25 Ultra"). |
| **Slug** | Yes | Auto-generated from name. URL-friendly identifier. |
| **SKU** | No | Stock Keeping Unit. Unique product code (e.g., "SAM-S25U-001"). |
| **Category** | Yes | Select from your categories. Searchable dropdown. |
| **Brand** | No | Select from your brands. Searchable dropdown. |
| **Seller** | No | Assign to a seller (for multi-vendor setup). |
| **Short Description** | No | Brief summary shown in product listings (max 500 characters). |
| **Description** | No | Full product description with rich text editor (bold, lists, images, etc.). |
| **Tags** | No | Add keywords for filtering. You can create new tags inline. |

---

### Tab 2: Pricing

| Field | Required | Description |
|-------|----------|-------------|
| **Price** | Yes | The selling price customers pay (e.g., ₹89,999.00). |
| **Compare at Price** | No | Original/MRP price before discount. Shown as strikethrough on storefront. Must be higher than Price. |
| **Cost Price** | No | Your purchase cost. Hidden from customers. Used for profit tracking. |

**Pricing Example:**

```
Price:            ₹89,999.00   ← Customer pays this
Compare at Price: ₹99,999.00   ← Shown as original price (strikethrough)
Cost Price:       ₹70,000.00   ← Your cost (hidden)

Customer sees:    ₹89,999  ̶₹̶9̶9̶,̶9̶9̶9̶  (10% OFF)
Your profit:      ₹19,999 per unit
```

---

### Tab 3: Inventory

| Field | Required | Description |
|-------|----------|-------------|
| **Stock Quantity** | Yes | Number of units available. Default: 0. |
| **Low Stock Threshold** | No | Alert when stock drops below this number. Default: 5. |
| **Barcode** | No | Product barcode (EAN, UPC, etc.). |
| **Weight** | No | Product weight (number). |
| **Weight Unit** | No | kg, g, lb, or oz. Default: kg. |
| **Length** | No | Product length in cm. |
| **Width** | No | Product width in cm. |
| **Height** | No | Product height in cm. |

> **Stock Color Indicators in Product List:**
> - 🟢 **Green** — In stock (above threshold)
> - 🟡 **Yellow** — Low stock (at or below threshold)
> - 🔴 **Red** — Out of stock (0 units)

---

### Tab 4: Options & Variants

This is where you define product variations like **Color, Size, Storage**, etc.

See [Section 5: Product Variants](#5-product-variants) for detailed instructions.

---

### Tab 5: Status & Visibility

| Field | Required | Description |
|-------|----------|-------------|
| **Status** | Yes | `Draft` (hidden), `Active` (live), or `Inactive` (temporarily hidden). Default: Draft. |
| **Is Featured** | — | Toggle ON to display on homepage featured section. |
| **Is Digital** | — | Toggle ON for downloadable/digital products (no shipping). |
| **Digital File Path** | No | File URL for digital products. Only visible when "Is Digital" is ON. |
| **Published At** | No | Schedule when the product goes live. |

**Product Statuses:**

| Status | Visible on Storefront | Use Case |
|--------|----------------------|----------|
| **Draft** | No | Work in progress. Still adding details. |
| **Active** | Yes | Live and available for purchase. |
| **Inactive** | No | Temporarily hidden (seasonal, out of stock, etc.). |

---

### Tab 6: SEO

| Field | Description |
|-------|-------------|
| **Meta Title** | Title shown in Google search results. Recommended: 50–60 characters. |
| **Meta Description** | Description shown in Google search results. Recommended: 150–160 characters. |

**Example:**
```
Meta Title:       Samsung Galaxy S25 Ultra - Buy Online at Best Price | PickKart
Meta Description: Buy Samsung Galaxy S25 Ultra with AI features, 200MP camera,
                  and titanium design. Free shipping, EMI available. Shop now on PickKart.
```

---

### Bulk Actions

In the product list, you can select multiple products and apply bulk actions:
- **Set Active** — Publish selected products
- **Set Inactive** — Hide selected products
- **Toggle Featured** — Add/remove from featured section
- **Delete** — Move to trash (soft delete)
- **Force Delete** — Permanently delete
- **Restore** — Recover deleted products

---

## 5. Product Variants

Variants allow a single product to have multiple options like different colors, sizes, or configurations.

### How Variants Work

PickKart supports up to **3 option levels** per product:

```
Option 1: Color     → Red, Blue, Black
Option 2: Size      → S, M, L, XL
Option 3: Material  → Cotton, Polyester
```

Each unique combination becomes a **variant** with its own price, SKU, and stock.

---

### Full Walkthrough: Adding a T-Shirt with Color & Size Variants

#### Step 1 — Create the Product

**Go to:** `https://yourdomain.com/admin/products/create`

**Or:** Sidebar > **Products** > Click **"New Product"** button (top right corner)

Fill in the **Basic Information** tab:

| Field | Value |
|-------|-------|
| Name | Nike Dri-FIT Running T-Shirt |
| SKU | NIKE-DRFIT-001 |
| Category | Men's Wear |
| Brand | Nike |
| Short Description | Lightweight running t-shirt with moisture-wicking technology |
| Description | (Add full product details using the rich text editor) |
| Tags | t-shirt, running, nike, sportswear |

#### Step 2 — Set Base Price

Click the **"Pricing"** tab (Tab 2):

| Field | Value |
|-------|-------|
| Price | 2,499.00 |
| Compare at Price | 3,499.00 |
| Cost Price | 1,200.00 |

#### Step 3 — Set Base Inventory

Click the **"Inventory"** tab (Tab 3):

| Field | Value |
|-------|-------|
| Stock Quantity | 100 |
| Low Stock Threshold | 10 |
| Weight | 0.25 |
| Weight Unit | kg |

#### Step 4 — Define Options

Click the **"Options & Variants"** tab (Tab 4).

You'll see a repeater field labeled **"Options"** with an **"Add to options"** button at the bottom.

**Add Option 1 — Color:**
1. Click **"Add to options"**
2. In the **Name** field, type: `Color`
3. In the **Values** field, type each value and press **Enter** after each one:
   - Type `Navy Blue` → press Enter
   - Type `Black` → press Enter
   - Type `White` → press Enter
   - Type `Red` → press Enter

You'll see the values appear as tags/chips:

```
Name:   Color
Values: [Navy Blue] [Black] [White] [Red]
```

**Add Option 2 — Size:**
1. Click **"Add to options"** again
2. **Name:** `Size`
3. **Values:** Type each and press Enter:
   - `S` → Enter
   - `M` → Enter
   - `L` → Enter
   - `XL` → Enter

```
Name:   Size
Values: [S] [M] [L] [XL]
```

> You can add up to **3 options** maximum. Each option can have unlimited values.

#### Step 5 — Set Status & Save

Click the **"Status & Visibility"** tab (Tab 5):

| Field | Value |
|-------|-------|
| Status | Draft (keep as draft until variants are ready) |
| Is Featured | OFF (for now) |

Now click **"Create"** button at the bottom right to **save the product**.

> **IMPORTANT:** You MUST save the product first before you can generate variants. The Generate Variants button only appears on the edit page after the product is saved.

#### Step 6 — Generate Variants

After saving, you'll be redirected to the product **edit** page:

**URL:** `https://yourdomain.com/admin/products/{id}/edit`

**Scroll down below the 6 tabs.** You'll see two sections:
- **Variants** (relation manager table)
- **Images** (relation manager table)

In the **Variants** section header:

1. Click the **"Generate Variants"** button (top-right of the Variants table)
2. A confirmation dialog appears: *"Are you sure?"*
3. Click **"Confirm"**
4. A green notification appears: *"16 variants created successfully"*

The system automatically creates **all possible combinations**:

```
┌─────────────┬──────────┬─────────────────────┐
│   Color     │   Size   │   Variant Name      │
├─────────────┼──────────┼─────────────────────┤
│ Navy Blue   │   S      │ Navy Blue / S        │
│ Navy Blue   │   M      │ Navy Blue / M        │
│ Navy Blue   │   L      │ Navy Blue / L        │
│ Navy Blue   │   XL     │ Navy Blue / XL       │
│ Black       │   S      │ Black / S            │
│ Black       │   M      │ Black / M            │
│ Black       │   L      │ Black / L            │
│ Black       │   XL     │ Black / XL           │
│ White       │   S      │ White / S            │
│ White       │   M      │ White / M            │
│ White       │   L      │ White / L            │
│ White       │   XL     │ White / XL           │
│ Red         │   S      │ Red / S              │
│ Red         │   M      │ Red / M              │
│ Red         │   L      │ Red / L              │
│ Red         │   XL     │ Red / XL             │
└─────────────┴──────────┴─────────────────────┘
Total: 4 colors × 4 sizes = 16 variants
```

#### Step 7 — Edit Individual Variants

Each variant now appears as a row in the table. Click the **pencil (edit) icon** on any row to customize it.

**Example — Editing variant "Navy Blue / M":**

| Field | Value |
|-------|-------|
| **Name** | Navy Blue / M (auto-generated, you can rename) |
| **SKU** | NIKE-DRFIT-NB-M |
| **Option 1 (Color)** | Navy Blue (pre-filled) |
| **Option 2 (Size)** | M (pre-filled) |
| **Price** | 2,499 (leave empty to use base product price) |
| **Compare at Price** | 3,499 |
| **Stock Quantity** | 20 |
| **Is Active** | ON |
| **Sort Order** | 0 |

Click **Save** to update the variant.

**Complete variant pricing table example:**

| Variant | SKU | Price | Stock |
|---------|-----|-------|-------|
| Navy Blue / S | NIKE-DRFIT-NB-S | 2,499 | 15 |
| Navy Blue / M | NIKE-DRFIT-NB-M | 2,499 | 20 |
| Navy Blue / L | NIKE-DRFIT-NB-L | 2,499 | 20 |
| Navy Blue / XL | NIKE-DRFIT-NB-XL | **2,699** | 10 |
| Black / S | NIKE-DRFIT-BK-S | 2,499 | 15 |
| Black / M | NIKE-DRFIT-BK-M | 2,499 | 25 |
| Black / L | NIKE-DRFIT-BK-L | 2,499 | 25 |
| Black / XL | NIKE-DRFIT-BK-XL | **2,699** | 10 |
| White / S | NIKE-DRFIT-WH-S | 2,499 | 10 |
| White / M | NIKE-DRFIT-WH-M | 2,499 | 15 |
| White / L | NIKE-DRFIT-WH-L | 2,499 | 15 |
| White / XL | NIKE-DRFIT-WH-XL | **2,699** | 8 |
| Red / S | NIKE-DRFIT-RD-S | 2,499 | 10 |
| Red / M | NIKE-DRFIT-RD-M | 2,499 | 15 |
| Red / L | NIKE-DRFIT-RD-L | 2,499 | 15 |
| Red / XL | NIKE-DRFIT-RD-XL | **2,699** | 8 |

> **Note:** XL sizes cost ₹2,699 (₹200 more) — each variant can have its own price.

#### Step 8 — Add Product Images

Scroll down to the **Images** section (below Variants).

Click **"New Image"** and add:

| # | Image URL | Alt Text | Primary | Sort |
|---|-----------|----------|---------|------|
| 1 | https://yourdomain.com/storage/products/nike-tshirt-front.jpg | Nike Dri-FIT T-Shirt front view | ON | 1 |
| 2 | https://yourdomain.com/storage/products/nike-tshirt-back.jpg | Nike Dri-FIT T-Shirt back view | OFF | 2 |
| 3 | https://yourdomain.com/storage/products/nike-tshirt-detail.jpg | Nike Dri-FIT fabric close-up | OFF | 3 |

#### Step 9 — Activate the Product

1. Scroll back up to the **"Status & Visibility"** tab (Tab 5)
2. Change **Status** from `Draft` to **`Active`**
3. Toggle **Is Featured** to **ON** (if you want it on the homepage)
4. Click **"Save changes"**

Your product is now **live on the storefront** with all 16 variants!

---

### More Variant Examples

**Example 2: Electronics — Samsung Galaxy S25 Ultra**

```
Path: Admin > Products > New Product

Option 1: Color    → Titanium Black, Gray, Blue
Option 2: Storage  → 256GB, 512GB, 1TB
Result: 9 variants (3 × 3)

Variant pricing:
  Any Color / 256GB  → ₹89,999
  Any Color / 512GB  → ₹99,999
  Any Color / 1TB    → ₹1,19,999
```

**Example 3: Shoes — Nike Air Max 270**

```
Path: Admin > Products > New Product

Option 1: Color  → Black/White, All Black, Blue/Gray
Option 2: Size   → UK 7, UK 8, UK 9, UK 10, UK 11
Result: 15 variants (3 × 5)

All variants same price: ₹12,999
```

**Example 4: Furniture — 3 Options**

```
Path: Admin > Products > New Product

Option 1: Color     → Walnut, Oak, White
Option 2: Size      → Small, Medium, Large
Option 3: Material  → Wood, MDF
Result: 18 variants (3 × 3 × 2)
```

---

### Adding New Variant Values Later

If you need to add a new color or size after the product is already live:

1. Go to **Admin > Products** > click on the product
2. Go to **Tab 4: Options & Variants**
3. Add the new value (e.g., add `Green` to the Color option)
4. Click **Save**
5. Scroll down to **Variants** section
6. Click **"Generate Variants"** again

The system will **only create new combinations** (e.g., Green/S, Green/M, Green/L, Green/XL) without duplicating existing ones.

---

### Disabling a Specific Variant

If a variant goes out of stock or is discontinued:

1. Go to the product edit page
2. Scroll to **Variants** section
3. Click the **edit icon** on the variant
4. Toggle **Is Active** to **OFF**
5. Save

The variant will be hidden from the storefront but remains in your records.

---

### Visual Navigation Summary

```
CREATING A PRODUCT WITH VARIANTS — Step by Step

Sidebar > Products > "New Product"
│
├── Tab 1: Basic Information
│   └── Name, SKU, Category, Brand, Description, Tags
│
├── Tab 2: Pricing
│   └── Price, Compare at Price, Cost Price
│
├── Tab 3: Inventory
│   └── Stock, Low Stock Threshold, Weight, Dimensions
│
├── Tab 4: Options & Variants  ← DEFINE OPTIONS HERE
│   └── Click "Add to options"
│   └── Option 1: Color → [Navy Blue] [Black] [White] [Red]
│   └── Click "Add to options"
│   └── Option 2: Size → [S] [M] [L] [XL]
│
├── Tab 5: Status & Visibility
│   └── Status: Draft → Click "Create" to SAVE
│
├── Tab 6: SEO
│   └── Meta Title, Meta Description
│
│   ──── AFTER SAVING, SCROLL DOWN ────
│
├── Variants Section  ← GENERATE & EDIT VARIANTS HERE
│   └── Click "Generate Variants" → Creates all combinations
│   └── Click edit icon on each → Set SKU, Price, Stock
│
└── Images Section  ← ADD PRODUCT IMAGES HERE
    └── Click "New Image" → Add URL, Alt Text, set Primary
```

---

## 6. Product Images

After creating a product, add images from the **Images** section at the bottom of the product edit page.

### Adding an Image

| Field | Required | Description |
|-------|----------|-------------|
| **Image URL** | Yes | Full URL to the image file (e.g., `https://yourdomain.com/storage/products/phone-front.jpg`). |
| **Alt Text** | No | Description for accessibility and SEO (e.g., "Samsung Galaxy S25 front view"). |
| **Sort Order** | No | Display order. Lower numbers appear first. |
| **Is Primary** | — | Toggle ON for the main product image. Only one should be primary. |

### Image Best Practices

- Upload images to your server's `/storage/products/` folder first, then use the URL.
- Use **square images** (1:1 ratio) for consistent display — recommended size: **800×800 pixels**.
- Add **2–5 images** per product (front, back, side, detail, lifestyle).
- Always set **one image as primary** — this is the thumbnail shown in product listings.
- Fill in **Alt Text** for better SEO and accessibility.

---

## 7. Managing Orders

**Navigate to:** Admin > Orders

### Order Information

Each order contains:
- **Order Number** — Unique identifier (auto-generated)
- **Customer** — Name, email, and contact details
- **Items** — Products ordered with quantities and prices
- **Status** — Current order status
- **Payment Status** — Paid, pending, or failed
- **Shipping Address** — Delivery destination
- **Order Total** — Subtotal + shipping - discounts

### Order Statuses

| Status | Description |
|--------|-------------|
| **Pending** | Order placed, awaiting processing |
| **Processing** | Order confirmed and being prepared |
| **Shipped** | Order dispatched to customer |
| **Delivered** | Order successfully delivered |
| **Cancelled** | Order cancelled by customer or admin |
| **Refunded** | Payment returned to customer |

---

## 8. Managing Coupons

Create discount codes for your customers.

**Navigate to:** Admin > Coupons > **New Coupon**

| Field | Description |
|-------|-------------|
| **Code** | Unique coupon code (e.g., "PICKKART10", "SUMMER25"). |
| **Type** | Percentage discount or Fixed amount. |
| **Value** | Discount value (e.g., 10 for 10% or 500 for ₹500 off). |
| **Min Order Amount** | Minimum cart value required to use the coupon. |
| **Max Uses** | Total number of times the coupon can be used. |
| **Max Uses Per User** | Limit per customer. |
| **Start Date** | When the coupon becomes active. |
| **End Date** | When the coupon expires. |
| **Is Active** | Toggle ON/OFF. |

**Example Coupons:**

| Code | Type | Value | Min Order | Description |
|------|------|-------|-----------|-------------|
| PICKKART10 | Percentage | 10% | ₹999 | 10% off on orders above ₹999 |
| FLAT500 | Fixed | ₹500 | ₹2,999 | ₹500 off on orders above ₹2,999 |
| WELCOME20 | Percentage | 20% | ₹499 | Welcome discount for new customers |

---

## 9. Managing Banners

Banners appear on the homepage slider/hero section.

**Navigate to:** Admin > Banners > **New Banner**

| Field | Description |
|-------|-------------|
| **Title** | Banner heading text. |
| **Subtitle** | Supporting text below the title. |
| **Image** | Banner image (recommended: 1920×600 pixels). |
| **Link** | URL when the banner is clicked (e.g., `/products?category=electronics`). |
| **Sort Order** | Display order in the slider. |
| **Is Active** | Toggle ON/OFF. |

---

## 10. Managing Pages

Create static pages like About Us, Privacy Policy, Terms & Conditions, etc.

**Navigate to:** Admin > Pages > **New Page**

| Field | Description |
|-------|-------------|
| **Title** | Page title (e.g., "About Us"). Slug is auto-generated. |
| **Slug** | URL path (e.g., `about-us` → yourdomain.com/pages/about-us). |
| **Content** | Rich text editor for page content. |
| **Meta Title** | SEO title. |
| **Meta Description** | SEO description. |
| **Is Active** | Toggle ON to publish. |

---

## 11. Managing Reviews

Moderate customer reviews for products.

**Navigate to:** Admin > Reviews

| Field | Description |
|-------|-------------|
| **Product** | The product being reviewed. |
| **Customer** | The reviewer. |
| **Rating** | 1–5 stars. |
| **Comment** | Review text. |
| **Status** | Pending, Approved, or Rejected. |

> Only **approved** reviews are visible on the storefront.

---

## 12. Managing Users & Roles

**Navigate to:** Admin > Users

### User Roles

| Role | Access Level |
|------|-------------|
| **Super Admin** | Full access to everything. Can manage other admins. |
| **Admin** | Full admin panel access. |
| **Seller** | Can manage their own products and view their orders. |
| **Customer** | Storefront access only. Can place orders, write reviews. |

### Creating a User

| Field | Description |
|-------|-------------|
| **Name** | Full name. |
| **Email** | Login email (must be unique). |
| **Password** | Account password. |
| **Role** | Select role: super-admin, admin, seller, or customer. |

---

## 13. SEO Settings

PickKart has built-in SEO features for better search engine visibility.

### What's Automatically Handled

- **Meta Tags** — Open Graph (Facebook) and Twitter Card tags on every page.
- **Canonical URLs** — Prevents duplicate content issues.
- **JSON-LD Structured Data** — Product schema, Organization schema, BreadcrumbList.
- **XML Sitemaps** — Auto-generated at `/sitemap.xml` (products, categories, pages).
- **robots.txt** — Dynamic, served at `/robots.txt`.

### Per-Page SEO Fields

Available on **Products, Categories, and Pages**:
- **Meta Title** — Keep it under 60 characters.
- **Meta Description** — Keep it under 160 characters.

### SEO Tips

1. Write unique meta titles and descriptions for every product.
2. Use descriptive **Alt Text** on all product images.
3. Create meaningful category descriptions.
4. Keep URLs clean (slugs are auto-generated from names).
5. Mark filtered/paginated listing pages as `noindex` (handled automatically).

---

## 14. Recommended Setup Order

When setting up a new store, follow this order:

```
Step 1: Categories
  └── Create parent categories first
  └── Then create subcategories

Step 2: Brands
  └── Add all your brands with logos

Step 3: Products
  └── Create products (assign category + brand)
  └── Save the product
  └── Generate Variants (if applicable)
  └── Add Product Images
  └── Set status to "Active" when ready

Step 4: Banners
  └── Add homepage banners with links to categories/products

Step 5: Pages
  └── Create About Us, Privacy Policy, Terms, Contact pages

Step 6: Coupons
  └── Create welcome/launch discount codes

Step 7: Review & Go Live
  └── Check all products are Active
  └── Verify images are loading
  └── Test a sample order
  └── Share your store!
```

---

## Quick Reference

| Action | Path |
|--------|------|
| Create Category | Admin > Categories > New Category |
| Create Brand | Admin > Brands > New Brand |
| Create Product | Admin > Products > New Product |
| Create Coupon | Admin > Coupons > New Coupon |
| Manage Orders | Admin > Orders |
| Create Banner | Admin > Banners > New Banner |
| Create Page | Admin > Pages > New Page |
| Manage Reviews | Admin > Reviews |
| Manage Users | Admin > Users |

---

*PickKart Admin Guide — Last updated: February 2026*
