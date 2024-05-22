<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ 'plugins/ecommerce::shipping.shipping_label.name'|trans }} {{ shipment.code }}</title>

        {{ settings.font_css }}

        <style>
            @page {
                margin: 0;
            }

            * {
                margin: 0;
            }

            body {
                font-size: 14px;
                font-family: '{{ settings.font_family }}', Arial, sans-serif !important;
            }

            table {
                border-collapse: collapse;
                width: 100%
            }

            table tr td {
                padding: 0
            }

            {{ settings.extra_css }}
        </style>

        {{ settings.header_html }}
    </head>
    <body>
        <div style="height: 94%; border: 3px solid black; margin: 20px; border-radius: 4px;">
            <div style="padding: 20px; border-bottom: 1px solid black;">
                <table>
                    <tr>
                        <td style="vertical-align: top; width: 18%;">
                            {{ 'plugins/ecommerce::shipping.shipping_label.sender'|trans }}:
                        </td>
                        <td style="vertical-align: top">
                            <h4>{{ sender.name }}</h4>
                            <p>{{ sender.full_address }}</p>
                            <p>{{ sender.phone }}</p>
                            <p>{{ sender.email }}</p>
                        </td>
                        <td style="vertical-align: top; width: 10%">
                            <img src="{{ sender.logo }}" alt="{{ sender.name }}" style="max-width: 120px; width: 100%: height: auto;">
                        </td>
                    </tr>
                </table>
            </div>

            <div style="padding: 20px; border-bottom: 1px solid black;">
                <h2 style="margin-bottom: 10px">{{ receiver.name }}</h2>
                <h4 style="margin-bottom: 6px">{{ receiver.full_address }}</h4>
                <h4 style="margin-bottom: 6px">{{ receiver.email }}</h4>
                <h4 style="margin-bottom: 6px">{{ receiver.phone }}</h4>
            </div>

            <div style="padding: 20px; border-bottom: 1px solid black">
                <table>
                    <tr>
                        <td style="padding-bottom: 14px;">
                            <span>{{ 'plugins/ecommerce::shipping.shipment_id'|trans }}:</span>
                            <h3>{{ shipment.code }}</h3>
                        </td>
                        <td style="padding-bottom: 14px;">
                            <span>{{ 'plugins/ecommerce::shipping.order_id'|trans }}:</span>
                            <h3>{{ shipment.order_number }}</h3>
                        </td>
                        <td style="padding-bottom: 14px;">
                            <span>{{ 'plugins/ecommerce::shipping.shipping_label.order_date'|trans }}:</span>
                            <h3>{{ shipment.created_at }}</h3>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>{{ 'plugins/ecommerce::shipping.shipping_method'|trans }}:</span>
                            <h3>{{ shipment.shipping_method }}</h3>
                        </td>
                        <td>
                            <span>{{ 'plugins/ecommerce::shipping.weight_unit'|trans({unit: shipment.weight_unit}) }}:</span>
                            <h3>{{ shipment.weight }} {{ shipment.weight_unit }}</h3>
                        </td>
                        <td>
                            <span>{{ 'plugins/ecommerce::shipping.shipping_fee'|trans }}:</span>
                            <h3>{{ shipment.shipping_fee }}</h3>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="padding: 20px;">
                {% if shipment.note %}
                    <div style="margin-bottom: 6px; overflow-wrap: break-word;">
                        <span>{{ 'plugins/ecommerce::shipping.note'|trans }}:</span>
                        <strong>{{ shipment.note }}</strong>
                    </div>
                {% endif %}

                <table style="margin-top: 20px">
                    <tr>
                        <td>
                            <img src="data:image/svg+xml;base64,{{ shipment.qr_code }}" style="max-height: 160px; width: auto%; height: auto;">
                        </td>
                        <td style="font-size: 12px;">
                            {{ 'plugins/ecommerce::shipping.shipping_label.scan_qr_code'|trans }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{ settings.footer_html }}
    </body>
</html>
