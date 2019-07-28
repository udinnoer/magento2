/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
require([
    "jquery",
    'mage/template',
    'jquery/ui'
], function(
        $,
        mage,
        ui
    ){
    "use strict";

    $.widget('mage.citykecamatancustomer', {
        options: {
            countryId : 'country_id',
            regionId : 'region_id',
            cityId : 'city',
            cityCopy : 'cityCopy',
            kecamatan : 'kecamatan',
            cityListCode : '',
            ajaxCity : '/citylist/ajax/citylist',
            ajaxKecamatan : '/citylist/ajax/kecamatanlist',
            ajaxCityCode : '/citylist/ajax/regionlist',
        },
        _create: function () {

            var checkInterval;
            var that = this;

            checkInterval = setInterval(function () {

                var loaderContainer = $('select[name="' + that.options.countryId + '"]');

                //Return if loader still load
                if (loaderContainer.length == 0) {
                    return;
                }
            
                var country = $('select[name="' + that.options.countryId + '"]').val();
                if(country != "ID") {
                    clearInterval(checkInterval);

                    that.bindCountry();

                    return false;
                }
            
                //Remove loader and clear update interval if content loaded
                if (loaderContainer.length > 0 ) {
                    clearInterval(checkInterval);

                    //get city dropdown
                    that.getCityDropdown();

                    //get city list dropdown
                    that.getCityListDropdown();

                    that.getKecamatanDropdown();
                    
                    that.getDefaultCity();
                    
                    that.getSelectedCity();

                    that.getSelectedKecamatan();
                }

            }, 100);

        },
        getCityDropdown:function(){
            var that = this;

            $('select[name="' + this.options.regionId + '"]').change(function(){
                window.shippingRegion = $(this).val();
                
                $.ajax({
                    method: "GET",
                    url: that.options.ajaxCity,
                    data: { region: $(this).val()}
                }).done(function( response ) {
                    $('select[name="cityCopy"]').html(response.message);
                });
            });

        },
        getCityListDropdown: function(){
            var that = this,
                valueExistSTATE = $('input[name="region"]').val();
            
            $('input[name="' + this.options.cityId + '"]').hide();      
            $('input[name="' + this.options.cityId + '"]').parent().append('<select class="select" name="cityCopy" placeholder=""></select>');
            
            if(typeof valueExistSTATE === "undefined" || valueExistSTATE =="") {
                $('select[name="cityCopy"]').append($("<option></option").attr("value", "").text('Pilih Kota'));
            }

            $('select[name="' + this.options.cityCopy + '"]').change(function(){
                var thatselect = $(this);

                $('input[name="city"]').val('');
                $('input[name="city"]').trigger('keyup');

                that.getKecamantanyListDropdown(that.options.ajaxKecamatan,$(this).val(),thatselect,that);
            });
        },
        getKecamantanyListDropdown:function(url,dataValue,el,that){
            
            $.ajax({
                method: "GET",
                url: url,
                data: { city: dataValue},
                beforeSend: function() {    
                                           
               },
               complete: function() {
               }
            }).done(function( response ) {
                el.parents('form').find('select[name="kecamatan"]').html(response.message);
            });
        },
        getDefaultCity:function(valueExistCITY) {
            var checkInterval,
                getStateID,
                urlajaxCity = '/citylist/ajax/citylist',
                valueExistCITY = $('input[name="city"]').val();
            
             checkInterval = setInterval(function () {
                 
                var loaderContainer = $('select[name="region_id"] option');

                //Return if loader still load
                if (loaderContainer.length <= 1) {
                    return;
                }
                //Remove loader and clear update interval if content loaded
                if (loaderContainer.length > 1 ) {
                    clearInterval(checkInterval);

                    var getStateID = $('select[name="region_id"]').val();

                    if(typeof getStateID !== "undefined") {
                        window.shippingRegion = getStateID;
                        
                        $.ajax({
                            method: "GET",
                            url: urlajaxCity,
                            data: { region: getStateID}
                        }).done(function( response ) {
                            $('select[name="cityCopy"]').html(response.message);
                        });                     
                        
                    }
                }

                }, 100);
        },
        getSelectedCity:function(){
            var checkInterval;
            var valueExistCITY = $('input[name="city"]').val();

            var existingCity = valueExistCITY.split('/');
            var currentCity = existingCity[0];
            var currentKecamatan = existingCity[1];
            
            checkInterval = setInterval(function () {
                 
                var loaderContainer = $('select[name="cityCopy"] option');

                //Return if loader still load
                if (loaderContainer.length <= 1) {
                    return;
                }
                //Remove loader and clear update interval if content loaded
                if (loaderContainer.length > 1 ) {
                    clearInterval(checkInterval);
                    
                    $('select[name="cityCopy"] option').each(function() {
                      if($(this).val() == currentCity) {
                        $(this).attr('selected', 'selected').trigger('change');            
                      }                        
                    });
                }
             
            }, 100);
        },
        getSelectedKecamatan:function(){
            var checkInterval;
            var valueExistCITY = $('input[name="city"]').val();

            var existingCity = valueExistCITY.split('/');
            var currentKecamatan = existingCity[1];
            
            checkInterval = setInterval(function () {
             
                var loaderContainer = $('select[name="kecamatan"] option');

                //Return if loader still load
                if (loaderContainer.length <= 1) {
                    return;
                }
                //Remove loader and clear update interval if content loaded
                if (loaderContainer.length > 1 ) {
                    clearInterval(checkInterval);
                    
                    $('select[name="kecamatan"] option').each(function() {
                      if($(this).val() == currentKecamatan) {
                        $(this).attr('selected', 'selected');        
                      }                        
                    });
                }
             
            }, 100);
        },
        getKecamatanDropdown: function(){
            var that = this;
            
            var div = '<div class="field kecamatan required">';
            div += '<label class="label"><span>Kecamatan</span></label>';
            div += '<div class="control"><select class="select" name="kecamatan" placeholder=""><option value="">Pilih Kecamatan</option></select></div>';
            div += '</div>';

            $('input[name="' + this.options.cityId + '"]').parents('.field').after(div);

            $('select[name="' + this.options.kecamatan + '"]').change(function(){ 
                var cityVal = $(this).parents('form').find('select[name="' + that.options.cityCopy + '"]').val();
                var kecamatanVal = $(this).parents('form').find('select[name="' + that.options.kecamatan + '"]').val();
                var cityKecamatanVal = '';

                if(cityVal != "" && kecamatanVal != ""){   
                    cityKecamatanVal = cityVal + "/" +  kecamatanVal;  
                        
                    $(this).parents('form').find('input[name="city"]').val(cityKecamatanVal);
                }
            });
        },
        bindCountry:function(){
            var that = this;

            $('select[name="' + this.options.countryId + '"]').change(function(){
                var thatselect = $(this);

                if(thatselect.val() == "ID") {
                    //get city dropdown
                    that.getCityDropdown();

                    //get city list dropdown
                    that.getCityListDropdown();

                    that.getKecamatanDropdown();
                } else {
                    that.removeCityKecamatan();
                }
            });
        },
        removeCityKecamatan : function (){
            $('select[name="' + this.options.cityCopy + '"]').remove();
            $('select[name="' + this.options.kecamatan + '"]').remove();
            $('input[name="' + this.options.cityId + '"]').show();
        },
        resetProvince:function(){
            $('select[name="cityCopy"]').html('<option value="">Please select a region, state or province.</option>');
        },
        resetCity:function(){
            $('select[name="cityCopy"]').html('<option value="">Pilih Kota</option>');
            $('input[name="city"]').val('');
        },
    });
    
    $(document).citykecamatancustomer();
});
