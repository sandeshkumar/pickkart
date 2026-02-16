<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'meta_title' => 'About Us - PickKart | Shivam Technologies',
                'meta_description' => 'Learn about PickKart by Shivam Technologies - your trusted online shopping destination for quality products at great prices.',
                'is_active' => true,
                'content' => <<<'HTML'
<h2>Welcome to PickKart</h2>
<p><strong>PickKart</strong> is an initiative by <strong>Shivam Technologies</strong>, a forward-thinking technology company committed to making online shopping accessible, affordable, and delightful for everyone across India.</p>

<h3>Our Story</h3>
<p>Founded with a simple vision — to bring quality products to every doorstep at fair prices — PickKart was born out of the belief that online shopping should be easy, trustworthy, and enjoyable. What started as a small venture has grown into a full-fledged e-commerce platform serving customers across India.</p>
<p>At Shivam Technologies, we combine our expertise in software development and digital innovation to build platforms that truly serve people. PickKart is our flagship e-commerce brand, and we pour our passion for technology and customer satisfaction into every aspect of the experience.</p>

<h3>What We Offer</h3>
<ul>
    <li><strong>Wide Product Range</strong> — From electronics and fashion to home essentials and beauty products, we curate a diverse selection to meet every need.</li>
    <li><strong>Quality Assurance</strong> — Every product on PickKart is sourced from verified sellers and trusted brands. We stand behind the quality of what we sell.</li>
    <li><strong>Best Prices</strong> — We work directly with brands and manufacturers to ensure you get the most competitive prices, with regular deals and discounts.</li>
    <li><strong>Fast & Free Shipping</strong> — Enjoy free shipping on orders over ₹499, with reliable delivery across India.</li>
    <li><strong>Hassle-Free Returns</strong> — Not satisfied? Our 30-day return policy makes exchanges and refunds simple and stress-free.</li>
    <li><strong>Secure Payments</strong> — Shop with confidence using UPI, credit/debit cards, net banking, or cash on delivery.</li>
</ul>

<h3>Our Mission</h3>
<p>To empower every Indian household with access to quality products through a seamless, secure, and affordable online shopping experience.</p>

<h3>Our Values</h3>
<ul>
    <li><strong>Customer First</strong> — Every decision we make starts with the question: "Is this good for our customers?"</li>
    <li><strong>Trust & Transparency</strong> — We believe in honest pricing, genuine products, and clear communication.</li>
    <li><strong>Innovation</strong> — As a technology company, we continuously improve our platform to deliver a better shopping experience.</li>
    <li><strong>Community</strong> — We support local sellers and Indian brands, helping them reach customers nationwide.</li>
</ul>

<h3>About Shivam Technologies</h3>
<p><strong>Shivam Technologies</strong> is a technology company specializing in web development, mobile applications, and e-commerce solutions. With a team of skilled developers and designers, we build digital products that solve real-world problems. PickKart is our consumer-facing brand that showcases our commitment to building technology that serves people.</p>

<h3>Get in Touch</h3>
<p>We'd love to hear from you! Whether you have a question, suggestion, or just want to say hello:</p>
<ul>
    <li><strong>Email:</strong> support@pickkart.co.in</li>
    <li><strong>Website:</strong> <a href="https://pickkart.co.in">pickkart.co.in</a></li>
</ul>
<p>Thank you for choosing PickKart. Happy shopping!</p>
HTML,
            ],
            [
                'title' => 'Shipping Policy',
                'slug' => 'shipping-policy',
                'meta_title' => 'Shipping Policy - PickKart',
                'meta_description' => 'Learn about PickKart shipping policy including delivery timelines, free shipping, and tracking information.',
                'is_active' => true,
                'content' => <<<'HTML'
<p><em>Last updated: February 2026</em></p>
<p>At <strong>PickKart</strong> (operated by <strong>Shivam Technologies</strong>), we strive to deliver your orders quickly, safely, and affordably. Please read our shipping policy below.</p>

<h3>1. Shipping Coverage</h3>
<p>We currently ship to all serviceable pin codes across India. During checkout, you can verify if delivery is available to your area by entering your pin code.</p>

<h3>2. Shipping Charges</h3>
<table>
    <thead>
        <tr><th>Order Value</th><th>Shipping Charge</th></tr>
    </thead>
    <tbody>
        <tr><td>Above ₹499</td><td><strong>FREE</strong></td></tr>
        <tr><td>Below ₹499</td><td>₹49</td></tr>
    </tbody>
</table>
<p><em>Note: Shipping charges may vary for bulky items, remote locations, or expedited delivery options.</em></p>

<h3>3. Estimated Delivery Time</h3>
<table>
    <thead>
        <tr><th>Location</th><th>Estimated Delivery</th></tr>
    </thead>
    <tbody>
        <tr><td>Metro Cities (Mumbai, Delhi, Bangalore, etc.)</td><td>2-4 business days</td></tr>
        <tr><td>Tier 2 Cities</td><td>3-5 business days</td></tr>
        <tr><td>Tier 3 Cities & Rural Areas</td><td>5-7 business days</td></tr>
        <tr><td>Remote/Hilly Areas</td><td>7-10 business days</td></tr>
    </tbody>
</table>
<p><em>Business days exclude Sundays and public holidays. Delivery times are estimates and may vary due to unforeseen circumstances.</em></p>

<h3>4. Order Processing</h3>
<ul>
    <li>Orders placed before <strong>2:00 PM IST</strong> are typically processed the same business day.</li>
    <li>Orders placed after 2:00 PM or on weekends/holidays will be processed the next business day.</li>
    <li>You will receive an email/SMS confirmation once your order has been dispatched.</li>
</ul>

<h3>5. Order Tracking</h3>
<p>Once your order is shipped, you will receive a tracking number via email and SMS. You can track your order:</p>
<ul>
    <li>Through the "My Orders" section in your PickKart account</li>
    <li>Using the tracking link provided in your shipping confirmation email</li>
    <li>By contacting our support team at <strong>support@pickkart.co.in</strong></li>
</ul>

<h3>6. Delivery Attempts</h3>
<ul>
    <li>Our delivery partners will make up to <strong>2 delivery attempts</strong>.</li>
    <li>If delivery fails after 2 attempts, the order will be returned to our warehouse.</li>
    <li>Please ensure someone is available at the delivery address to receive the package.</li>
</ul>

<h3>7. Damaged or Missing Items</h3>
<p>If you receive a damaged package or items are missing from your order:</p>
<ul>
    <li>Contact us within <strong>48 hours</strong> of delivery at support@pickkart.co.in</li>
    <li>Include your order number and photos of the damaged item/package</li>
    <li>We will arrange a replacement or full refund at no extra cost</li>
</ul>

<h3>8. Cash on Delivery (COD)</h3>
<p>Cash on Delivery is available for most pin codes. COD orders have the same shipping charges and delivery timelines as prepaid orders. Please keep the exact amount ready at the time of delivery.</p>

<h3>9. Contact Us</h3>
<p>For any shipping-related queries, please reach out to us:</p>
<ul>
    <li><strong>Email:</strong> support@pickkart.co.in</li>
    <li><strong>Website:</strong> <a href="https://pickkart.co.in">pickkart.co.in</a></li>
</ul>
HTML,
            ],
            [
                'title' => 'Returns & Refunds',
                'slug' => 'return-policy',
                'meta_title' => 'Returns & Refunds Policy - PickKart',
                'meta_description' => 'PickKart returns and refunds policy. 30-day hassle-free returns on most products.',
                'is_active' => true,
                'content' => <<<'HTML'
<p><em>Last updated: February 2026</em></p>
<p>At <strong>PickKart</strong> (operated by <strong>Shivam Technologies</strong>), customer satisfaction is our top priority. If you're not completely happy with your purchase, we're here to help.</p>

<h3>1. Return Policy Overview</h3>
<ul>
    <li><strong>Return Window:</strong> 30 days from the date of delivery</li>
    <li><strong>Condition:</strong> Items must be unused, unworn, and in their original packaging with all tags attached</li>
    <li><strong>Proof of Purchase:</strong> Original invoice or order confirmation is required</li>
</ul>

<h3>2. Eligible for Returns</h3>
<ul>
    <li>Defective or damaged products</li>
    <li>Wrong item received</li>
    <li>Product significantly different from the description</li>
    <li>Size/fit issues (for clothing and footwear)</li>
    <li>Change of mind (within 7 days, item must be unused)</li>
</ul>

<h3>3. Not Eligible for Returns</h3>
<ul>
    <li>Intimate apparel, swimwear, and undergarments</li>
    <li>Customized or personalized products</li>
    <li>Perishable goods (food, flowers, etc.)</li>
    <li>Digital downloads and gift cards</li>
    <li>Products with broken seals (perfumes, cosmetics opened)</li>
    <li>Items marked as "Non-Returnable" on the product page</li>
</ul>

<h3>4. How to Initiate a Return</h3>
<ol>
    <li><strong>Contact Us:</strong> Email support@pickkart.co.in with your order number, the item(s) you wish to return, and the reason for return</li>
    <li><strong>Approval:</strong> Our team will review your request and respond within 24-48 hours</li>
    <li><strong>Ship the Item:</strong> Once approved, pack the item securely in its original packaging and ship it to the address provided, or our delivery partner will arrange a pickup</li>
    <li><strong>Inspection:</strong> Upon receiving the returned item, we will inspect it within 2-3 business days</li>
    <li><strong>Refund/Exchange:</strong> Once approved, your refund or exchange will be processed</li>
</ol>

<h3>5. Refund Options</h3>
<table>
    <thead>
        <tr><th>Payment Method</th><th>Refund Method</th><th>Timeline</th></tr>
    </thead>
    <tbody>
        <tr><td>UPI / Net Banking</td><td>Original payment method</td><td>5-7 business days</td></tr>
        <tr><td>Credit/Debit Card</td><td>Original card</td><td>7-10 business days</td></tr>
        <tr><td>Cash on Delivery</td><td>Bank transfer (NEFT/IMPS)</td><td>5-7 business days</td></tr>
        <tr><td>Any method</td><td>Store credit (optional)</td><td>Instant</td></tr>
    </tbody>
</table>
<p><em>Refund timelines begin after the returned item passes inspection.</em></p>

<h3>6. Exchange Policy</h3>
<p>If you'd like to exchange an item for a different size, colour, or variant:</p>
<ul>
    <li>Exchanges are subject to availability of the requested item</li>
    <li>If the replacement item is more expensive, you'll need to pay the difference</li>
    <li>If it's less expensive, the difference will be refunded</li>
</ul>

<h3>7. Return Shipping Costs</h3>
<ul>
    <li><strong>Defective/Wrong Items:</strong> Return shipping is FREE (we arrange pickup)</li>
    <li><strong>Change of Mind:</strong> Return shipping cost of ₹49 will be deducted from the refund</li>
</ul>

<h3>8. Cancellation Policy</h3>
<ul>
    <li>Orders can be cancelled anytime <strong>before dispatch</strong> for a full refund</li>
    <li>Once dispatched, orders cannot be cancelled but can be returned after delivery</li>
</ul>

<h3>9. Contact Us</h3>
<p>For return or refund queries:</p>
<ul>
    <li><strong>Email:</strong> support@pickkart.co.in</li>
    <li><strong>Website:</strong> <a href="https://pickkart.co.in">pickkart.co.in</a></li>
</ul>
HTML,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'meta_title' => 'Privacy Policy - PickKart',
                'meta_description' => 'PickKart privacy policy. Learn how we collect, use, and protect your personal information.',
                'is_active' => true,
                'content' => <<<'HTML'
<p><em>Last updated: February 2026</em></p>
<p><strong>Shivam Technologies</strong> ("we", "us", "our") operates the <strong>PickKart</strong> website at <a href="https://pickkart.co.in">pickkart.co.in</a> ("the Site"). This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our Site and make purchases.</p>

<h3>1. Information We Collect</h3>
<h4>Personal Information</h4>
<p>When you create an account, place an order, or contact us, we may collect:</p>
<ul>
    <li>Full name</li>
    <li>Email address</li>
    <li>Phone number</li>
    <li>Shipping and billing address</li>
    <li>Payment information (processed securely through our payment partners)</li>
</ul>

<h4>Automatically Collected Information</h4>
<p>When you browse our Site, we may automatically collect:</p>
<ul>
    <li>IP address and browser type</li>
    <li>Device information (type, operating system)</li>
    <li>Pages visited and time spent on the Site</li>
    <li>Referring website or link</li>
    <li>Cookies and similar tracking technologies</li>
</ul>

<h3>2. How We Use Your Information</h3>
<p>We use the information we collect to:</p>
<ul>
    <li>Process and fulfil your orders</li>
    <li>Send order confirmations, shipping updates, and delivery notifications</li>
    <li>Create and manage your account</li>
    <li>Provide customer support and respond to enquiries</li>
    <li>Improve our website, products, and services</li>
    <li>Send promotional offers and newsletters (with your consent)</li>
    <li>Prevent fraud and ensure security</li>
    <li>Comply with legal obligations</li>
</ul>

<h3>3. Information Sharing</h3>
<p>We do <strong>not sell</strong> your personal information to third parties. We may share your information with:</p>
<ul>
    <li><strong>Shipping Partners:</strong> To deliver your orders (name, address, phone number)</li>
    <li><strong>Payment Processors:</strong> To process payments securely (Razorpay, UPI providers)</li>
    <li><strong>Analytics Providers:</strong> To understand website usage (anonymized data)</li>
    <li><strong>Legal Authorities:</strong> When required by law or to protect our rights</li>
</ul>

<h3>4. Cookies</h3>
<p>We use cookies to:</p>
<ul>
    <li>Keep you logged in to your account</li>
    <li>Remember items in your cart</li>
    <li>Understand how you use our Site (analytics)</li>
    <li>Personalise your shopping experience</li>
</ul>
<p>You can control cookies through your browser settings. Disabling cookies may affect some features of our Site.</p>

<h3>5. Data Security</h3>
<p>We implement industry-standard security measures to protect your information:</p>
<ul>
    <li>SSL encryption for all data transmission</li>
    <li>Secure payment processing through PCI-DSS compliant partners</li>
    <li>Regular security audits and updates</li>
    <li>Access controls limiting who can view personal data</li>
</ul>
<p>While we take every precaution, no method of transmission over the Internet is 100% secure. We cannot guarantee absolute security of your data.</p>

<h3>6. Your Rights</h3>
<p>You have the right to:</p>
<ul>
    <li><strong>Access</strong> your personal data we hold</li>
    <li><strong>Correct</strong> inaccurate or incomplete data</li>
    <li><strong>Delete</strong> your account and associated data</li>
    <li><strong>Opt out</strong> of marketing communications at any time</li>
    <li><strong>Withdraw consent</strong> for data processing where applicable</li>
</ul>
<p>To exercise these rights, email us at <strong>support@pickkart.co.in</strong>.</p>

<h3>7. Third-Party Links</h3>
<p>Our Site may contain links to third-party websites. We are not responsible for the privacy practices of these external sites. We encourage you to read their privacy policies.</p>

<h3>8. Children's Privacy</h3>
<p>Our Site is not intended for children under 18 years of age. We do not knowingly collect personal information from children. If we discover that a child has provided us with personal information, we will delete it immediately.</p>

<h3>9. Changes to This Policy</h3>
<p>We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated "Last updated" date. We encourage you to review this policy periodically.</p>

<h3>10. Contact Us</h3>
<p>If you have questions about this Privacy Policy or your personal data:</p>
<ul>
    <li><strong>Company:</strong> Shivam Technologies</li>
    <li><strong>Email:</strong> support@pickkart.co.in</li>
    <li><strong>Website:</strong> <a href="https://pickkart.co.in">pickkart.co.in</a></li>
</ul>
HTML,
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms',
                'meta_title' => 'Terms & Conditions - PickKart',
                'meta_description' => 'PickKart terms and conditions. Read the rules and guidelines for using our e-commerce platform.',
                'is_active' => true,
                'content' => <<<'HTML'
<p><em>Last updated: February 2026</em></p>
<p>Welcome to <strong>PickKart</strong> (<a href="https://pickkart.co.in">pickkart.co.in</a>), operated by <strong>Shivam Technologies</strong>. By accessing or using our website, you agree to be bound by these Terms and Conditions. Please read them carefully.</p>

<h3>1. Definitions</h3>
<ul>
    <li><strong>"Site"</strong> refers to the PickKart website at pickkart.co.in</li>
    <li><strong>"We", "Us", "Our"</strong> refers to Shivam Technologies</li>
    <li><strong>"User", "You", "Your"</strong> refers to any person accessing or using the Site</li>
    <li><strong>"Products"</strong> refers to goods available for purchase on the Site</li>
</ul>

<h3>2. Account Registration</h3>
<ul>
    <li>You must be at least 18 years old to create an account and make purchases</li>
    <li>You are responsible for maintaining the confidentiality of your account credentials</li>
    <li>You agree to provide accurate and complete information during registration</li>
    <li>We reserve the right to suspend or terminate accounts that violate these terms</li>
</ul>

<h3>3. Products and Pricing</h3>
<ul>
    <li>All product descriptions, images, and specifications are provided as accurately as possible. However, we do not guarantee that descriptions are error-free</li>
    <li>Prices are listed in Indian Rupees (₹) and include applicable taxes unless stated otherwise</li>
    <li>We reserve the right to modify prices at any time without prior notice</li>
    <li>In the event of a pricing error, we reserve the right to cancel the order and issue a full refund</li>
</ul>

<h3>4. Orders and Payment</h3>
<ul>
    <li>Placing an order constitutes an offer to purchase. We may accept or decline orders at our discretion</li>
    <li>Order confirmation does not guarantee availability. If a product is out of stock after order placement, we will notify you and offer alternatives or a full refund</li>
    <li>We accept payments via UPI, credit/debit cards, net banking, wallets, and Cash on Delivery (COD)</li>
    <li>All online payments are processed through secure, PCI-DSS compliant payment gateways</li>
</ul>

<h3>5. Shipping and Delivery</h3>
<p>Please refer to our <a href="/pages/shipping-policy">Shipping Policy</a> for detailed information on delivery timelines, charges, and tracking.</p>

<h3>6. Returns and Refunds</h3>
<p>Please refer to our <a href="/pages/return-policy">Returns & Refunds Policy</a> for detailed information on how to return products and receive refunds.</p>

<h3>7. User Conduct</h3>
<p>By using our Site, you agree NOT to:</p>
<ul>
    <li>Use the Site for any unlawful purpose</li>
    <li>Attempt to gain unauthorized access to our systems or other users' accounts</li>
    <li>Submit false information or impersonate others</li>
    <li>Post defamatory, offensive, or misleading reviews</li>
    <li>Use automated tools (bots, scrapers) to access the Site</li>
    <li>Interfere with the proper functioning of the Site</li>
</ul>

<h3>8. Intellectual Property</h3>
<ul>
    <li>All content on the Site — including logos, text, images, graphics, and software — is the property of Shivam Technologies or its licensors</li>
    <li>You may not reproduce, distribute, or create derivative works from our content without written permission</li>
    <li>The "PickKart" name and logo are trademarks of Shivam Technologies</li>
</ul>

<h3>9. Reviews and User Content</h3>
<ul>
    <li>By submitting reviews, you grant us a non-exclusive, royalty-free right to use, display, and share your content</li>
    <li>Reviews must be honest, relevant, and not contain offensive, defamatory, or illegal content</li>
    <li>We reserve the right to remove reviews that violate these guidelines</li>
</ul>

<h3>10. Limitation of Liability</h3>
<ul>
    <li>PickKart and Shivam Technologies shall not be liable for any indirect, incidental, or consequential damages arising from your use of the Site</li>
    <li>Our total liability for any claim shall not exceed the amount paid by you for the specific product or service in question</li>
    <li>We are not responsible for delays or failures caused by events beyond our reasonable control (force majeure)</li>
</ul>

<h3>11. Indemnification</h3>
<p>You agree to indemnify and hold harmless Shivam Technologies, its officers, employees, and partners from any claims, damages, or expenses arising from your use of the Site or violation of these Terms.</p>

<h3>12. Governing Law</h3>
<p>These Terms shall be governed by and construed in accordance with the laws of India. Any disputes arising from these Terms shall be subject to the exclusive jurisdiction of the courts in India.</p>

<h3>13. Changes to These Terms</h3>
<p>We reserve the right to update these Terms at any time. Changes will be posted on this page with an updated date. Continued use of the Site after changes constitutes acceptance of the revised Terms.</p>

<h3>14. Severability</h3>
<p>If any provision of these Terms is found to be invalid or unenforceable, the remaining provisions shall continue in full force and effect.</p>

<h3>15. Contact Us</h3>
<p>For questions about these Terms and Conditions:</p>
<ul>
    <li><strong>Company:</strong> Shivam Technologies</li>
    <li><strong>Email:</strong> support@pickkart.co.in</li>
    <li><strong>Website:</strong> <a href="https://pickkart.co.in">pickkart.co.in</a></li>
</ul>
HTML,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
