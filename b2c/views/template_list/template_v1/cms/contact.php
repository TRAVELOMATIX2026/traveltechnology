<style>
.signupfm { margin : 5px auto; }
.signupfm li {
    float: left;
    padding: 0px 10px;
}
.signupfm li a {
    font-size:20px;
}

</style>
<div class = "row">
<div class = "col-md-4">
<h3>Our Location</h3>

<table>
	<tbody>
		<tr>
			<td>
			<p><?php echo $this->application_address; ?></p>
			<!-- <p> Gulfview Executive Homes, Bago Aplaya </p>
			<p> Davao City </p> -->
			<p> Tel.: <?= $this->entity_domain_phone_code . ' ' . $this->entity_domain_phone ?></p>
			</td>
		</tr>
		
	</tbody>
</table>

<h3>Quick Contact</h3>

<table>
	<tbody>
		<tr>
			<td>
			<p>Email : <?= $this->entity_domain_mail ?>,</p>
			</td>
		</tr>
		<tr>
			<td>
			<p>TEL:<?= $this->entity_domain_phone_code . ' ' . $this->entity_domain_phone ?></p>
			</td>
		</tr>
		<tr>
			<td><p>WhatsApp: <a href="https://wa.me/<?=str_replace("+","",$this->entity_domain_phone_code).str_replace("-","",$this->entity_domain_phone)?>"><?= $this->entity_domain_phone_code . ' ' . $this->entity_domain_phone ?></a></p></td>
		</tr>
	</tbody>
</table>
<h3>Office Hours:</h3>
<table>
	<tbody>
		<tr>
			<td>
			<p>Monday - Friday: 10:00 AM - 3:00 PM</p>
			</td>
		</tr>
		<tr>
			<td>
			<p>Saturday: 10:00 AM - 2:00 PM</p>
			</td>
		</tr>
		<tr>
			<td><p> Sunday: Closed </p> </td>
		</tr>
	</tbody>
</table>

<!-- <h3>Follow us on:</h3>
<ul class="signupfm">
<li><a href="https://www.facebook.com/romeo.jumalon/" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
<li><a href="https://twitter.com/JumalonRom68929" target="_blank"><i class="fab fa-twitter"></i></a></li>
<li><a href="https://www.instagram.com/jumalonromeo/" target="_blank"><i class="fab fa-instagram"></i></a></li>
</ul> -->


</div>
<div class="col-md-8"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3144.702626914013!2d145.1709841753671!3d-37.98406864378957!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad6137baa5955d9%3A0x13045674ec090401!2s48-46%20Wahroonga%20Ave%2C%20Keysborough%20VIC%203173%2C%20Australia!5e0!3m2!1sen!2sin!4v1765432466277!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
</div>
