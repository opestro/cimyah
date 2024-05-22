{{ header }}

<div class="bb-main-content">
    <table class="bb-box" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
            <td class="bb-content bb-pb-0" align="center">
                <table class="bb-icon bb-icon-lg bb-bg-blue" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td valign="middle" align="center">
                                <img src="{{ 'wallet' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-center bb-m-0 bb-mt-md">Invoice Payment Detail</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content">
                <p class="h1">Dear {{ customer_name }},</p>
                <p>You're receiving email from <strong>{{ site_title }}</p>

                {% if invoice_link %}
                    <p>The invoice <a href="{{ invoice_link }}">{{ invoice_code }}</a> is attached with this email.</p>

                    <div class="bb-pt-md bb-text-center">
                        <a href="{{ invoice_link }}" class="bb-btn bb-bg-blue bb-border-blue">
                            <span class="btn-span">View Online</span>
                        </a>
                    </div>
                {% else %}
                    <p>The invoice {{ invoice_code }} is attached with this email.</p>
                {% endif %}
            </td>
        </tr>
        </tbody>
    </table>
</div>

{{ footer }}
