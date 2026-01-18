<style>.modal-header .close {right: 15px;font-weight: 500;}</style>
<div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> Payment Details</h4>
            </div>
            <div class="modal-body">
        	    <div id="payform_parameters">
                    <div class="pay_frm">
                        <div class="row">
                            <div class="col-sm-12" id="ticket_confirm_response">
                            </div>
                        </div>
                        <form id="payform" method="post">
                            <input type="hidden" name="book_id" id="book_id" required>
                            <input type="hidden" name="book_source" id="book_source" required>
                            <input type="hidden" name="cust_card_type" id="card_type" required>
                            <div class="form-group row" id="card-number-field">
                                <!-- <label for="cardNo" class="col-sm-3 nopad">Total Amount </label> -->
                                <div class="offset-sm-3 col-sm-6 nopad text-center">
                                    <h3>Pay <span class="text-bold" id="pay_amount"></span> </h3>
                                </div>
                            </div>
                            <div class="form-group row" id="card-number-field">
                                <label for="cardNo" class="col-sm-3 nopad">Card Number </label>
                                <div class="col-sm-6 nopad">
                                    <input type="text" name="cust_card_number" class="form-control" id="cardNo" value="" placeholder="Card Number" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ownrName" class="col-sm-3 nopad">Card Holder’s Name </label>
                                <div class="col-sm-6 nopad">
                                    <input type="text" name="cust_card_holder_name" class="form-control" id="ownrName" placeholder="Card Holder Name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ownrName" class="col-sm-3 nopad">Expiry Date </label>
                                <div class="col-sm-6 nopad">
                                    <div class="col-6 nopad slct_dt_wrppr">
                                        <div class="slc_dt">
                                            <select class="form-control" name="cust_card_expiry_month" id="card_expiry_month" required>
                                                <option value="">MM</option>
                                                <option value="1">JAN(01)</option>
                                                <option value="2">FEB(02)</option>
                                                <option value="3">MAR(03)</option>
                                                <option value="4">APR(04)</option>
                                                <option value="5">MAY(05)</option>
                                                <option value="6">JUN(06)</option>
                                                <option value="7">JUL(07)</option>
                                                <option value="8">AUG(08)</option>
                                                <option value="9">SEP(09)</option>
                                                <option value="10">OCT(10)</option>
                                                <option value="11">NOV(11)</option>
                                                <option value="12">DEC(12)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 nopad slct_dt_wrppr">
                                        <div class="slc_dt">
                                            <select class="form-control" name="cust_card_expiry_year" id="card_expiry_year" required>
                                                <option value=''>Year</option>
                                                <?php for($i=date("Y");$i<date("Y")+20;$i++){ ?>
                                                <option value='<?=$i?>'><?=$i?></option>
                                            <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row card_div">
                                <label for="ownrName" class="col-sm-3 nopad">CVV </label>
                                <div class="col-sm-6 nopad">
                                    <div class="col-9 nopad">
                                        <input type="text" name="cust_card_cvv" class="form-control" id="cvv" placeholder="CVV" required>
                                    </div>
                                    <div class="col-3 nopad">
                                        <i class="fal fa-credit-card"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row card_div">
                                <div class="offset-sm-3 col-sm-6 nopad">
                                    <input type="button" value="Submit" class="btn btn-success" id="payment_cnf_btn">
                                </div>
                            </div>
                        </form>
                    </div>
				</div>
            </div>
        </div>
    </div>
</div>
<script src="<?=base_url('../extras/system/library/card_validation/jquery.payform.min.js?v=1')?>" charset="utf-8"></script>
<script src="<?=base_url('../extras/system/library/card_validation/script.js?v=2')?>"></script>