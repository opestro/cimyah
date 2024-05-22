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
                                <img src="{{ 'hourglass' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">Pending product approval</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <p>Dear Admin,</p>
                    <div>New product was created by {{ store_name }} <a href="{{ product_url }}">{{ product_name }}</a> is waiting for approval.</div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
