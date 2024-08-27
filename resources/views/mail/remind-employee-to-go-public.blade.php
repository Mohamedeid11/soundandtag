<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    </head>
    <body style="margin: 0;">
        <style>
            @font-face {
                font-family: Futura;
                src: url("{{asset('fonts/FuturaPTBook.woff')}}") format("WOFF");
                font-weight: 400;
                font-display: swap;
            }
            .ExternalClass { width: 100%; }
        </style>

        <table cellpadding="0" cellspacing="0" border="0" style="
            max-width: 700px;
            background: radial-gradient(circle,#031839 0%,#00101f 40%) #00101f; 
            padding: 8px 10%;
            box-sizing: border-box;
            margin: 0 auto;
            text-align: center;
            color: #fff;
            font-family: Futura;">

            <tr>
                <td>
                    <h2 style="text-transform: uppercase; 
                            letter-spacing: 16px;
                            font-size: 16px"> 
                        Welcome 
                    </h2>
                </td>
            </tr>

            <tr>
                <td>
                    <img src="{{asset('/images/img/logo-1.png')}}" 
                        style="margin: 60px auto 40px; display: block;"
                        width="250" 
                        alt="Logo">
                </td>
            </tr>

            <tr>
                <td>
                    <h3>
                        <span style="font-weight: 400; font-size: 16px">Dear.</span>
                    </h3>
                </td>
            </tr>

            <tr>
                <td>
                    <p style="width: 80%; margin: 16px auto"> 
                        Your corporate, 
                        <a href="{{route('web.profile', ['username'=> $username ])}}" target="_blank" style="color: #63caf7; text-decoration: none">{{$name}}</a>, 
                        wants you to fix your sound profile issues and complete your information so your profile can be public.
                    </p>
                </td>
            </tr>

            <tr>
                <td>
                    <p style="width: 80%; margin: 16px auto"> Regards </p>
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