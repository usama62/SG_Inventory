{{-- Shams Universal Trading header — matches letterhead design --}}
<table class="shams-header" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td colspan="2" class="gold-bar">&nbsp;</td>
    </tr>
    <tr>
        <td class="header-left" width="57%" valign="middle">
            <table cellspacing="0" cellpadding="0">
                <tr>
                    <td class="logo-cell" valign="middle">
                        @if(!empty($logoSrc))
                            <img src="{{ $logoSrc }}" alt="Shams" width="80">
                        @endif
                    </td>
                    <td valign="middle">
                        <div class="name-ar">{{ $companyNameAr ?? 'شمس يونيفرسال تريدينغ ش.م.ح-ذ.م.م' }}</div>
                        <div class="name-main">{{ $companyNameMain ?? 'SHAMS UNIVERSAL' }}</div>
                        <div class="name-sub">{{ $companyNameSub ?? 'T R A D I N G F Z - L L C' }}</div>
                    </td>
                </tr>
            </table>
        </td>
        <td class="header-right" width="43%" valign="middle">
            <table class="contact-table" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="icon-cell" width="18">
                        @if(!empty($iconPhoneSrc))<img src="{{ $iconPhoneSrc }}" width="13" height="13" alt="">@endif
                    </td>
                    <td>{{ $headerPhoneLandline ?? '+971 4 335 8029' }}</td>
                </tr>
                <tr>
                    <td class="icon-cell">
                        @if(!empty($iconMobileSrc))<img src="{{ $iconMobileSrc }}" width="13" height="13" alt="">@endif
                    </td>
                    <td>{{ $headerPhoneMobile ?? '+971 56 409 0798' }}</td>
                </tr>
                <tr>
                    <td class="icon-cell">
                        @if(!empty($iconEmailSrc))<img src="{{ $iconEmailSrc }}" width="13" height="13" alt="">@endif
                    </td>
                    <td>{{ $headerEmail ?? 'sufiyanjetham@shamsglobalfzllc.ae' }}</td>
                </tr>
                <tr>
                    <td class="icon-cell">
                        @if(!empty($iconWebSrc))<img src="{{ $iconWebSrc }}" width="13" height="13" alt="">@endif
                    </td>
                    <td>{{ $headerWebsiteDisplay ?? 'shamsglobalfzllc.ae' }}</td>
                </tr>
                <tr>
                    <td class="icon-cell">
                        @if(!empty($iconLocationSrc))<img src="{{ $iconLocationSrc }}" width="13" height="13" alt="">@endif
                    </td>
                    <td>{{ $headerAddressLine1 ?? 'Al Fattan Plaza, Office # 904' }}</td>
                </tr>
                <tr>
                    <td class="icon-cell">&nbsp;</td>
                    <td class="addr-indent">{{ $headerAddressLine2 ?? 'Office Building, Al Garhood, Dubai-U.A.E' }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
