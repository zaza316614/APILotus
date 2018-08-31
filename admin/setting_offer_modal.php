/**
 * Created by PhpStorm.
 * User: zaza3
 * Date: 8/29/2018
 * Time: 7:03 AM
 */

<div class="modal fade" id="offer_add_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="label_add_offer"></h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="alert alert-warning offer_add_alert" role="alert" style="display:none"></div>
                    <div class="alert alert-warning setting_campaign_alert" role="alert" style="display:none"></div>
                    <div class="row crm_board_row">
                        <div style="text-align:right; padding-right: 15px">
                            <div class="col-xs-2 modal_input_label">Name</div>
                            <div class="col-xs-10"><input type="text" class="form-control input-sm add_offer_name"></div>
                        </div>
                    </div>
                    <div class="row crm_board_row">
                        <div style="text-align:right; padding-right: 30px">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm search_campaign_ids" placeholder="Search by Campaign Id">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-sm campaign_search_button" type="button" style="width:100px"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;Search</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row crm_board_row">
                        <div class="col-xs-12" style="text-align:right; padding-right: 30px">
                            <div class="btn-group campaign_pagination" role="group">
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle count_toggle_button" data-toggle="dropdown" aria-expanded="false" style="width:60px">
                                    10 <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right count_dropdown_menu" role="menu" style="width: 80px !important; min-width: 80px !important">
                                    <li><a href="#">10</a></li>
                                    <li><a href="#">20</a></li>
                                    <li><a href="#">50</a></li>
                                    <li><a href="#">100</a></li>
                                    <li><a href="#">500</a></li>
                                    <li><a href="#">1000</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="div_select_campaign">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success modal_btn_offer_add">Add Offer</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="offer_delete_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Message</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    Do you want to delete this offer?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success modal_btn_offer_delete">Delete</button>
            </div>
        </div>
    </div>
</div>