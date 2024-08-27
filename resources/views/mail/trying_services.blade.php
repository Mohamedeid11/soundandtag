<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    </head>
    <body style="margin: 0;">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;700&display=swap');
            .ExternalClass { width: 100%; }
        </style>

        <table cellpadding="0" cellspacing="0" border="0" style="
            max-width: 700px;
            background: radial-gradient(circle,#031839 0%,#00101f 40%) #00101f;
            box-sizing: border-box;
            margin: 0 auto;
            font-family: Raleway;">

            <tr style="height: 330px">
                <td style="
                    background-image: url({{asset('/images/music_background.png')}});
                    background-position: center;
                    background-repeat: no-repeat;
                    vertical-align: middle;
                    text-align: center;
                    padding: 40px">
                    <img src="{{asset('/images/img/logo-1.png')}}" alt="Logo" style="width: 360px;">
                </td>
            </tr>

            <tr>
                <td>
                    <table cellpadding="0" cellspacing="0" border="0" style="
                        background: #fff;
                        padding: 0 16px;
                        max-width: 700px
                    ">
                        <tr>
                            <td colspan="2" style="padding: 40px 20px 0">
                                <h1 style="
                                    font-size: 30px;
                                    color: #55C7EF;
                                    width: 75%;
                                    margin: auto;
                                    text-align: center
                                ">
                                    Welcome To The Sound&Tag 15 Days Trial Service (Expires at {{$expires_at}})
                                </h1>
                            </td>
                        </tr>

                        <tr style="display: block">
                            <td style="
                                padding: 80px 40px 0 20px;
                                width: 50%;
                                display: block;
                                margin: auto;
                                text-align: center;"
                                >
                                <a href="{{ route(
                                    'web.getTryingUserProfile', ['user_id' => $user_id]
                                ) }}"
                                style="
                                    border: 2px solid #A6A8AB;
                                    display: block;
                                    border-radius: 20px;
                                    box-shadow: 6px 6px 10px rgba(166, 168, 171, 0.5)
                                    ">
                                    <img src="{{asset('storage/uploads/profile/card-'.$user_id.'.jpg')}}" style="width: 100%; border-radius: 20px;">
                                </a>
                                <p style="text-align: center; color: #58595B; font-size: 14px">Click this card to open your profile</p>
                            </td>
                            <td style="
                                padding-top: 20px;
                                padding-right: 20px;
                                display: block;
                                margin: auto;
                                text-align: center;
                            ">
                                <h2 style="color: #55C7EF; font-size: 24px; margin: 0">This is your trial</h2>
                                <h3 style="color: #55C7EF; font-size: 24px; margin: 0">Sound&Tag Profile Card</h3>
                                <p style="color: #58595B">The card is a link to your name recordings, and can be embedded in any HTML, such as your website, email signature or Word document. </p>
                                <p>
                                    <span style="width:5px; height: 5px; border-radius: 50%; display: inline-block; background: #55C7EF; margin-left: 10px"></span>
                                    <span style="width:5px; height: 5px; border-radius: 50%; display: inline-block; background: #55C7EF; margin-left: 10px"></span>
                                    <span style="width:5px; height: 5px; border-radius: 50%; display: inline-block; background: #55C7EF; margin-left: 10px"></span>
                                    <span style="width:5px; height: 5px; border-radius: 50%; display: inline-block; background: #55C7EF; margin-left: 10px"></span>
                                    <span style="width:5px; height: 5px; border-radius: 50%; display: inline-block; background: #55C7EF; margin-left: 10px"></span>
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 60px 20px 4px 20px; text-align: center;" colspan="2">
                                <a href="{{ route(
                                    'web.getTryingUserProfile', ['user_id' => $user_id]
                                ) }}" style="color: #265f9e">Click to Register</a>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" style="padding: 0 20px">
                                <h2 style="margin: 0; color: #55c7ef; text-align: center; font-size: 18px;">And get access to the following benefits:</h2>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 96px 0 40px" colspan="2">
                                <table cellpadding="12" cellspacing="0" border="0">
                                    <tr>
                                        <td style="text-align: center; vertical-align: top;">
                                            <img src="{{asset('images/mail_benefit_7.png')}}" style="height: 60px;">
                                            <p style="color: #404041; font-weight: 500; margin: 0;">Pronounce & listen to names correctly</p>
                                        </td>
                                        <td style="text-align: center; vertical-align: top;">
                                            <img src="{{asset('images/mail_benefit_1.png')}}" style="height: 60px;">
                                            <p style="color: #404041; font-weight: 500; margin: 0;">Add the meaning of your names or what is it related to</p>
                                        </td>
                                        <td style="text-align: center; vertical-align: top;">
                                            <img src="{{asset('images/mail_benefit_4.png')}}" style="height: 50px; margin-bottom: 10px">
                                            <p style="color: #404041; font-weight: 500; margin: 0;">Add your public data, full BIO</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; vertical-align: top;">
                                            <img src="{{asset('images/mail_benefit_5.png')}}" style="height: 60px;">
                                            <p style="color: #404041; font-weight: 500; margin: 0;">Create a QR code of your Sound&Tag card to include on physical business cards</p>
                                        </td>
                                        <td style="text-align: center; vertical-align: top;">
                                            <img src="{{asset('images/mail_benefit_6.png')}}" style="height: 60px;">
                                            <p style="color: #404041; font-weight: 500; margin: 0;">Share the card with your social or business network</p>
                                        </td>
                                        <td style="text-align: center; vertical-align: top;">
                                            <img src="{{asset('images/mail_benefit_8.png')}}" style="height: 60px;">
                                            <p style="color: #404041; font-weight: 500; margin: 0;">Request others to record send you their Sound&Tag</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="text-align: center; vertical-align: top;">
                                            <img src="{{asset('images/mail_benefit_2.png')}}" style="height: 60px;">
                                            <p style="color: #404041; font-weight: 500; margin: 0;">Choose the corporate option company name or brand and personals</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="padding: 80px 5% 60px; text-align: center">
                    <a href="{{ route(
                                    'web.getTryingUserProfile', ['user_id' => $user_id]
                                ) }}" style="color: #55C7EF; font-size: 20px;">Click to Register</a>
                </td>
            </tr>

            <tr>
                <td style="padding: 0 5% 80px">
                    <p style="text-align: center">
                        <img src="{{asset('/images/img/logo-1.png')}}" alt="Logo" style="width: 200px">
                    </p>
                    <p style="color: #92d8f2; text-align: center; margin: 0;">From the Sound&Tag Team</p>
                </td>
            </tr>

        </table>

        <table style="
            max-width: 700px;
            padding: 8px 0;
            color: #000;
            margin: 0 auto;
            font-family: Futura;">
            <tr>
                <td>
                    <hr>
                </td>
            </tr>
            <tr>
                <td style="font-size: 13px">
                    If you have any questions please feel free to contact us at support@soundandtag.com
                </td>
            </tr>
        </table>
    </body>
</html>
