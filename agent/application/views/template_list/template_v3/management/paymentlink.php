<!-- Mail - Voucher  starts-->
<div class="modal fade" id="mail_paymentlink_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope-o"></i>
					Send Payment link
				</h4>
			</div>
			<div class="modal-body">
				<div id="email_voucher_parameters">
					<input type="email" id="recipient_email" class="form-control" value="" required="required" placeholder="Enter Email">
					<input type="hidden" id="app_reference" class="form-control" value="<?= (($this->uri->segment(4))? $this->uri->segment(4): '');?>">
					<div class="payment_copy_sec">
						<div class="payment_text">Payment Link:
							<a href="<?= $tdetails['invoice_url']?>">
							<span class="paymentlink"><?= $tdetails['invoice_url']?></span>
						</div>
						<a title="Copy to clipboard" class="copyclipboard"><i class="far fa-copy"></i></a>
					</div>
					<p>Copy of Payment link will be sent to the above Email Id</p>
					<div class="row">
						<div class="col-md-4 nopad">
							<input type="button" value="SEND" class="btn btn-success" id="send_mail_btn">
						</div>
						<div class="col-md-8">

							<strong id="mail_error_message" class="text-danger"></strong>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Mail - Voucher  ends-->