<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Invoice</title>
	<style>
		@font-face {
			font-family: Futura;
			src: url("{{asset('fonts/FuturaPTBook.woff')}}") format("WOFF");
			font-weight: 400;
			font-display: swap;
		}
		@font-face {
            font-family: Futura;
            src: url("{{asset('fonts/FuturaPTMedium.woff')}}") format("WOFF");
            font-weight: 500;
            font-display: swap;
        }

		body {
			margin: 0 auto;
			color: #34373b;
			width: 90%;
			font-family: Futura
		}
		* {
			box-sizing: border-box;
		}

		/* Header */
		.Invoice_header {
			border-spacing: 0 32px;
			width: 100%;
			table-layout: fixed
		}
		/* Logo */
		.Invoice_header__logo {
			width: 200px;
		}
		/* Contact list */
		.Invoice_header__contact {
			width: 100%
		}
		.Invoice_header__contact__heading {
			font-size: 16px;
		}
		.Invoice_header__contact__list__item__term {
			color: #63caf7;
			font-weight: 500;
			margin-right: 16px;
		}
		.Invoice_header__contact__list__item__link {
			color: #34373b;
			text-decoration: none;
		}
		/* Heading */
		.Invoice_header__heading {
			background: #fff;
			padding: 20px;
			margin-left: auto;
			margin-right: 10%;
		}
		.Invoice_header__heading__text {
			background: #fff;
		}
		/* Buyer info */
		.Buyer_info {
			padding-bottom: 40px;
			border-bottom: 1px dashed #34373b;
			margin-bottom: 40px;
			font-size: 18px;
			border-spacing: 16px
		}
		.Buyer_info__heading, .Plans__heading {
			font-size: 2rem;
		}
		.Buyer_info__list__term {
			width: 40%;
		}
		.Buyer_info__list__desc {
			width: 60%;
			margin: 0;
			font-weight: 500;
		}
		/* Plans */
		.Plans {
			border-spacing: 16px;
		}
		.Plans__list__term {
			color: #fff;
			background: #63caf7;
			border-radius: 5px;
			padding: 16px 8px;
			width: 150px;
			text-transform: uppercase;
			font-size: 18px;
		}
		.Plans__list__desc {
			background: #ccc;
			border-radius: 5px;
			padding: 8px;
			width: 120px;
			font-weight: 500;
		}
		/* Footer */
		.Total {
			margin-top: 40px;
			margin-left: auto;
			margin-right: -5%;
			width: 300px;
			padding: 16px 10px;
			background: #63caf7;
			color: #fff;
			font-size: 20px;
			font-weight: 500;
		}
	</style>
</head>
<body>
	<!-- Header -->
	<table cellpadding="0" cellspacing="0" border="0" class="Invoice_header">
		<tr>
			<!-- Logo -->
			<td style="width: 60%">
				<img class="Invoice_header__logo" src="{{asset('/images/img/logo-dark.png')}}" alt="logo" />
			</td>

			<!-- Contact list -->
			<td>
				<table cellpadding="4" cellspacing="0" border="0" class="Invoice_header__contact">
					<tr>
						<td colspan="2">
							<h2 class="Invoice_header__contact__heading">CONTACT INFO</h2>
						</td>
					</tr>
					<tr>
						<td class="Invoice_header__contact__list__item__term">E:</td>
						<td>
							<a
							class="Invoice_header__contact__list__item__link"
							href="mailto:Support@Soundandtag.com">
								Support@Soundandtag.com
							</a>
						</td>
					</tr>
					<tr>
						<td class="Invoice_header__contact__list__item__term">E:</td>
						<td>
							<a
							class="Invoice_header__contact__list__item__link"
							href="mailto:Admin@Soundandtag.com">
								Admin@Soundandtag.com
							</a>
						</td>
					</tr>
					<tr>
						<td class="Invoice_header__contact__list__item__term">T:</td>
						<td>
							<a
							class="Invoice_header__contact__list__item__link"
							href="tel:+973 17820702">
								+973 17820702
							</a>
						</td>
					</tr>
					<tr>
						{{-- <td class="Invoice_header__contact__list__item__term">
							<img src="{{asset('/images/img/icon/location_icon-blue.svg')}}" alt="Location icon" />
						</td> --}}
                        <td class="Invoice_header__contact__list__item__term">L:</td>
						<td>
							Manama center Building Manama 307, Kingdom of Bahrain
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<!-- Heading -->
		<tr>
			<td colspan="2" style="background: #63caf7;">
			<table cellpadding="0" cellspacing="0" border="0" class="Invoice_header__heading">
				<tr>
					<td>
						<h1 class="Invoice_header__heading__text">PAYMENT RECEIPT</h1>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>

	<!-- Buyer info -->
	<table cellpadding="0" cellspacing="0" border="0" class="Buyer_info">
		<tr>
			<td colspan="2">
				<h2 class="Buyer_info__heading">Invoice to:</h2>
			</td>
		</tr>

		<tr>
			<td class="Buyer_info__list__term">Name</td>
			<td class="Buyer_info__list__desc">{{$user->name}}</td>
		</tr>

		<tr>
			<td class="Buyer_info__list__term">Address</td>
			<td class="Buyer_info__list__desc">{{$user->address? $user->address . ", " : ""}} {{$user->country->name}}</td>
		</tr>

		<tr>
			<td class="Buyer_info__list__term">Date</td>
			<td class="Buyer_info__list__desc">{{$user->plan->created_at->toDayDateTimeString()}}</td>
		</tr>

		<tr>
			<td class="Buyer_info__list__term">Payment Method</td>
			<td class="Buyer_info__list__desc">By Card</td>
		</tr>
	</table>

	<!-- Plans -->
	<table cellpadding="0" cellspacing="0" border="0" class="Plans">
		<tr>
			<td colspan="2">
				<h2 class="Plans__heading">Plan</h2>
			</td>
		</tr>

		<tr>
			<td class="Plans__list__term">{{ucfirst($user->account_type)}}</td>
			<td class="Plans__list__desc">
				<span>{{ucfirst($user->plan->plan->period)}}</span>
				<span>{{$user->plan->plan->price}} USD</span><br>
                @if($user->account_type == 'corporate')
                <span>{{$user->plan->plan->items}} Employees</span>
                @endif
			</td>
		</tr>
	</table>

	<!-- Footer -->
	<table cellpadding="0" cellspacing="0" border="0" class="Total">
		<tr>
			<td>Total</td>
			<td>{{$user->plan->payment->value < $user->plan->plan->price?
                (int) $user->plan->payment->value . ' (upgrade)' : (int) $user->plan->payment->value}}            </td>
		</tr>
	</table>
</body>
</html>
