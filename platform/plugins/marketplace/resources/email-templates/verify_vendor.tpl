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
                                    <img src="{{ 'check' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">Verify Vendor</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pb-0">
                    <p>Dear Admin,</p>
                    <p>You have a new vendor that needs to be verified on {{ site_title }}!</p>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pt-0 bb-pb-0">
                    <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td class="bb-bb-col">
                                <h4>Vendor Information</h4>
                                <div>Name: <strong>{{ customer_name }}</strong></div>
                                <div>Phone: <strong>{{ customer_phone }}</strong></div>
                                <div>Address: <strong>{{ customer_address }}</strong></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pt-0">
                    <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td class="bb-bb-col">
                                <h4>Shop information</h4>
                                <div>Store Name: <strong>{{ store_name }}</strong></div>
                                <div>Store Phone Number: <strong>{{ store_phone }}</strong></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center bb-pt-0 bb-pb-xl">
                    <table cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                            <td align="center">
                                <table cellpadding="0" cellspacing="0" border="0" class="bb-bg-blue bb-rounded bb-w-auto">
                                    <tr>
                                        <td align="center" valign="top" class="lh-1">
                                            <a href="{{ store_url }}" class="bb-btn bb-bg-blue bb-border-blue">
                                                <span class="btn-span">Visit store</span>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
