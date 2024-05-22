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
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">Account deletion confirmation</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <p>Dear {{ customer_name }},</p>
                    <div>We've received a request to delete the account associated with the email address <strong>{{ customer_email }}</strong>.</div>
                    <div>To confirm this action, please follow the link below. Once you've confirmed, your account will be deleted.</div>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center bb-pt-0 bb-pb-xl">
                    <table cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td align="center">
                                    <table cellpadding="0" cellspacing="0" border="0" class="bb-bg-blue bb-rounded bb-w-auto">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="top" class="lh-1">
                                                    <a href="{{ confirm_url }}" class="bb-btn bb-bg-red bb-border-red">
                                                        <span class="btn-span">Confirm&nbsp;delete account</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="bb-content bb-pb-0">Thank you for your cooperation. If you didn't initiate this request, please disregard this message.</div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
