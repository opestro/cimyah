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
                                <img src="{{ 'confetti' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">Welcome vendor</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pb-0">
                    <p>Dear {{ vendor_name }},</p>
                    <p>We're delighted to welcome you to {{ store_name }}!</p>

                    <p>Your vendor registration has been successfully completed, and you're now part of our vibrant community of sellers.</p>

                    <p>Here's what you can do next:</p>

                    <ol>
                        <li>Log in to your vendor account using your credentials.</li>
                        <li>Add your products/services to your store. Ensure to provide detailed descriptions and captivating images to attract potential buyers.</li>
                    </ol>

                    <p>If you have any questions or need assistance along the way, our support team is here to assist you. Feel free to reach out!</p>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center bb-pb-0">
                    <a href="{{ site_url }}" class="bb-btn bb-bg-blue">Log in to your vendor account</a>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center">
                    <div>We're excited to see your store flourish on {{ site_name }}!</div>
                </td>
            </tr>
        </tbody>
    </table>
</div>


{{ footer }}
