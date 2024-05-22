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
                                <img src="{{ 'alert-triangle' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-center bb-m-0 bb-mt-md">Account deletion completed</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content">
                <p>Dear {{ customer_name }},</p>
                <div>This is an automated email to let you know that your account has been deleted.</div>
                <div>If you have any questions, please don't hesitate to contact us.</div>
            </td>
        </tr>
        </tbody>
    </table>
</div>

{{ footer }}
