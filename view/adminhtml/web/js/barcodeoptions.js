define([
    "jquery",
    'mage/mage',
    "mage/translate"
], function ($,mage,$t) {
    'use strict';
    $.widget('mage.barcode_options', {
        _create: function () {
            var self = this;
            var count=self.options.count;
            $("#add_new_barcode").click(function () {
                $("#barcode_options_container").append('<form id="multi_barcode" method="post" autocomplete="off"><div class="option-box-barcode"><table cellpadding="0" class="barcode"><tr><td class="name_barcode"> <input class="required-entry input-text validate-length minimum-length-9 maximum-length-12" placeholder="At least 9 and no more than 12 characters"  data-form-part="product_form" name="barcodearr[name]['+count+']" id="name_barcode['+count+']" ></input> </td> <td class="qty_barcode"> <input class="validate-greater-than-zero required-entry required-number" name="barcodearr[qty]['+count+']" id="qty_barcode['+count+']" data-form-part="product_form"></input> </td> <td class="delete_barcode"><button class="lof_multibarcode_delete" type="button" data-todelete="4" title="Delete" id="delete_barcode">Delete</button></td> </tr></table></div></form>');
                count ++;
            });
            $("body").on('click','.add',function () {
                var this_this=$(this);
                var this_count=$(this_this).attr('data-count');
                var this_row_count = $(this_this).attr("data-thiscount");
                $(this_this).parents(".barcode_one_column").find(".price_qty_container").append("<tr><td><input data-form-part='product_form' type='text' name='barcode["+this_count+"][qty]["+this_row_count+"]' class='admin__control-text input-text required-entry validate-digits'></td><td><input data-form-part='product_form' type='text' name='barcode["+this_count+"][price]["+this_row_count+"]' class='admin__control-text input-text required-entry validate-zero-or-greater'></td><td class='last'><span title='Delete row'><button class='delete delete-select-row icon-btn' type='button' title='Delete Row'><span><span><span>"+$t('Row Delete')+"</span></span></span></button></span></td></tr>");
                this_row_count++;
                $(this_this).attr("data-thiscount",this_row_count);
            });
            $("body").on('click','.lof_multibarcode_delete',function () {
                var this_this=$(this);
                var id = $(this_this).attr("data-todelete-barcode");
                if (typeof id != "undefined") {
                    $(".barcode_options").append("<input data-form-part='product_form' type='hidden' name='todeletebarcode["+id+"]' value='"+id+"'/>");
                }
                $(this_this).parents(".option-box-barcode").remove();
            });
            $("body").on('click','.delete-select-row',function () {
                var this_this=$(this);
                var id = $(this_this).attr("data-todelete");
                if (typeof id != "undefined") {
                    $(".barcode_options").append("<input data-form-part='product_form' type='hidden' name='todelete["+id+"]' value='"+id+"'/>");
                }
                $(this_this).parents("tr").remove();
            });
        },
    });
    return $.mage.barcode_options;
});