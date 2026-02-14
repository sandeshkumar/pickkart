<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5; padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.1);">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#4f46e5; padding:24px 32px; text-align:center;">
                            <h1 style="margin:0; color:#ffffff; font-size:24px; font-weight:700;">PickKart</h1>
                        </td>
                    </tr>

                    {{-- Order Confirmed Banner --}}
                    <tr>
                        <td style="padding:32px 32px 16px; text-align:center;">
                            <div style="display:inline-block; background-color:#ecfdf5; border:1px solid #a7f3d0; border-radius:50%; width:56px; height:56px; line-height:56px; margin-bottom:16px;">
                                <span style="font-size:28px;">&#10003;</span>
                            </div>
                            <h2 style="margin:0 0 8px; color:#111827; font-size:22px; font-weight:700;">Order Confirmed!</h2>
                            <p style="margin:0; color:#6b7280; font-size:14px;">Thank you for your order. Here's a summary of what you purchased.</p>
                        </td>
                    </tr>

                    {{-- Order Number --}}
                    <tr>
                        <td style="padding:0 32px 24px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb; border-radius:8px; padding:16px;">
                                <tr>
                                    <td style="padding:12px 16px;">
                                        <span style="color:#6b7280; font-size:13px;">Order Number</span><br>
                                        <strong style="color:#111827; font-size:16px;">{{ $order->order_number }}</strong>
                                    </td>
                                    <td align="right" style="padding:12px 16px;">
                                        <span style="color:#6b7280; font-size:13px;">Date</span><br>
                                        <strong style="color:#111827; font-size:14px;">{{ $order->created_at->format('M d, Y') }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Order Items --}}
                    <tr>
                        <td style="padding:0 32px 24px;">
                            <h3 style="margin:0 0 12px; color:#111827; font-size:16px; font-weight:600;">Items Ordered</h3>
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #e5e7eb;">
                                @foreach($order->items as $item)
                                <tr style="border-bottom:1px solid #f3f4f6;">
                                    <td style="padding:12px 0;">
                                        <strong style="color:#111827; font-size:14px;">{{ $item->product_name }}</strong>
                                        @if($item->variant_name)
                                            <br><span style="color:#9ca3af; font-size:12px;">{{ $item->variant_name }}</span>
                                        @endif
                                        <br><span style="color:#6b7280; font-size:13px;">Qty: {{ $item->quantity }}</span>
                                    </td>
                                    <td align="right" style="padding:12px 0; color:#111827; font-size:14px; font-weight:600;">
                                        {{ format_currency($item->total) }}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    {{-- Order Totals --}}
                    <tr>
                        <td style="padding:0 32px 24px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb; border-radius:8px;">
                                <tr>
                                    <td style="padding:10px 16px; color:#6b7280; font-size:14px;">Subtotal</td>
                                    <td align="right" style="padding:10px 16px; color:#111827; font-size:14px;">{{ format_currency($order->subtotal) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 16px; color:#6b7280; font-size:14px;">Shipping</td>
                                    <td align="right" style="padding:10px 16px; color:#111827; font-size:14px;">{{ $order->shipping_amount == 0 ? 'Free' : format_currency($order->shipping_amount) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 16px; color:#6b7280; font-size:14px;">Tax</td>
                                    <td align="right" style="padding:10px 16px; color:#111827; font-size:14px;">{{ format_currency($order->tax_amount) }}</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td style="padding:10px 16px; color:#059669; font-size:14px;">Discount</td>
                                    <td align="right" style="padding:10px 16px; color:#059669; font-size:14px;">-{{ format_currency($order->discount_amount) }}</td>
                                </tr>
                                @endif
                                <tr style="border-top:2px solid #e5e7eb;">
                                    <td style="padding:12px 16px; color:#111827; font-size:16px; font-weight:700;">Total</td>
                                    <td align="right" style="padding:12px 16px; color:#111827; font-size:16px; font-weight:700;">{{ format_currency($order->total) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Shipping Address --}}
                    @if($order->shippingAddress)
                    <tr>
                        <td style="padding:0 32px 24px;">
                            <h3 style="margin:0 0 8px; color:#111827; font-size:16px; font-weight:600;">Shipping Address</h3>
                            <p style="margin:0; color:#6b7280; font-size:14px; line-height:1.6;">
                                {{ $order->shippingAddress->full_name }}<br>
                                {{ $order->shippingAddress->address_line_1 }}<br>
                                @if($order->shippingAddress->address_line_2)
                                    {{ $order->shippingAddress->address_line_2 }}<br>
                                @endif
                                {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}<br>
                                {{ $order->shippingAddress->country }}
                            </p>
                        </td>
                    </tr>
                    @endif

                    {{-- Payment Method --}}
                    <tr>
                        <td style="padding:0 32px 32px;">
                            <h3 style="margin:0 0 8px; color:#111827; font-size:16px; font-weight:600;">Payment Method</h3>
                            <p style="margin:0; color:#6b7280; font-size:14px;">
                                {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f9fafb; padding:24px 32px; text-align:center; border-top:1px solid #e5e7eb;">
                            <p style="margin:0 0 8px; color:#6b7280; font-size:13px;">
                                If you have any questions about your order, please contact us at support@pickkart.com
                            </p>
                            <p style="margin:0; color:#9ca3af; font-size:12px;">
                                &copy; {{ date('Y') }} PickKart. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
