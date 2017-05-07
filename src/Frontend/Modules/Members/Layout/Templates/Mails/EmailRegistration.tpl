{include:/Core/Layout/Templates/Mails/Header.tpl}

<h2>{$msgEmailRegistrationSubject}</h2>
<hr/><br/>
<p>{$msgEmailRegistration}</p>
<p>{$msgEmailRegistrationBody|sprintf:'{$SITE_URL}':'{$registrationUrl}'}</p>
<p>{$msgEmailRegistrationClosure}</p>

{include:/Core/Layout/Templates/Mails/Footer.tpl}
