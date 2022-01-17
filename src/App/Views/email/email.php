<div style="font-weight:bold; padding:15px">
<p>Успешна резервация № : #{reservation_id}</p>

<p>Моля да потвърждите вашата резервацията тук: <a href="{confirm_link}">{confirm_link}</a></p>

<p>Линкът е валиден 10 мин. !</p>
</div>

<div class="row" style="border:solid 1px cornflowerblue; padding:15px 5px">{display}</div>

<div>
<p>Име: {name}</p>

<p>Ако връзката по-горе не се отвори може да я копирате директно във вашият браузър:</p>

<p>{confirm_link}</p>

<div style="border:1px solid #505050; padding:10px">
<h4>{hotel_name}</h4>

<p>адрес: {address}</p>

<p>град: {city}</p>

<p>държава: {country}</p>

<p>тел: {phone}</p>

<p>email: {email}</p>

<p>сайт: {web}</p>
</div>

<p>If the above link is not clickable, try copying and pasting it into the address bar of your web browser.</p>
</div>