// JavaScript Document

jQuery(document).ready(function($){
	/*$('.range-slider').jRange({
		from: 1000,
		to: 9999000,
		step: 10000,
		scale: [1000,9999000],
		format: '%s',
		width: 400,
		showLabels: true,
		isRange : true
	});*/
	
	
	
	var slickopts = {
	  slidesToShow: 3,
	  slidesToScroll: 3,
	  dots: true,
	  arrows: false,
	  rows: 2, // Removes the linear order. Would expect card 5 to be on next row, not stacked in groups.
	  responsive: [
		{ breakpoint: 992,
		  settings: {
			slidesToShow: 3
		  }
		},
		{ breakpoint: 776,
		  settings: {
			slidesToShow: 1,
			rows: 1 // This doesn't appear to work in responsive (Mac/Chrome)
		  }
		}]
	};
	
	$('.carousel').slick(slickopts);
	
	
	
	var $range = $(".js-range-slider"),
		$from = $(".from"),
		$to = $(".to"),
		range,
		min = $range.data('min'),
		max = $range.data('max'),
		from,
		to;
	
	var updateValues = function () {
		$from.prop("value", from);
		$to.prop("value", to);
	};
	
	$range.ionRangeSlider({
		from: $from.val(),
		onChange: function (data) {
		  from = data.from;
		  to = data.to;
		  updateValues();
		}
	});
	
	range = $range.data("ionRangeSlider");
	var updateRange = function () {
		range.update({
			from: from,
			to: to
		});
	};
	
	$from.on("input", function () {
		from = +$(this).prop("value");
		if (from < min) {
			from = min;
		}
		if (from > to) {
			from = to;
		}
		updateValues();    
		updateRange();
	});
	
	$to.on("input", function () {
		to = +$(this).prop("value");
		/*if (to > max) {
			to = max;
		}
		if (to < from) {
			to = from;
		}*/
		updateValues();    
		updateRange();
	});
	
	
	
	
	$('#job_any_exp').on('click',function(){
        if(this.checked){
             $('.search_checkbox').each(function(){
                this.checked = false;
            });
        }
    });
    $('.search_checkbox').on('click',function(){
        if($('.search_checkbox:checked').length >= 1){
            $('#job_any_exp').prop('checked',false);
        }
    });
	
	
	$('#job_any_type').on('click',function(){
        if(this.checked){
             $('.search_checkbox2').each(function(){
                this.checked = false;
            });
        }
    });
	$('.search_checkbox2').on('click',function(){
        if($('.search_checkbox2:checked').length >= 1){
            $('#job_any_type').prop('checked',false);
        }
    });
	
	
	$("#posturjob").detach().insertAfter(".lastfield");
	$(".job_banner").detach().insertBefore("main.job_listing");
	
	
	getallmembers();
	/*$(".member_search_form__submit").click(function(){
		getallmembers();
	});*/
	
	
	$('#member_any_exp').on('click',function(){
        if(this.checked){
             $('input[name="experience[]"]').each(function(){
                this.checked = false;
            });
			getallmembers();
        }
    });
    $('input[name="experience[]"]').on('click',function(){
        if($('input[name="experience[]"]:checked').length >= 1){
            $('#member_any_exp').prop('checked',false);
        }
    });
	
	$(".member-search-form").submit(function(e){
		e.preventDefault();
		getallmembers();
	});
	
	$('input[name="category[]"]').change(function () {
		getallmembers();
	});
	
	$('input[name="experience[]"]').change(function () {
		getallmembers();
	});
	
	$('input[name="experience_years[]"]').change(function () {
		getallmembers();
	});
	
	$('input[name="role_level[]"]').change(function () {
		getallmembers();
	});
	
	$('input[name="skills[]"]').change(function () {
		getallmembers();
	});
	
	
	$( "body" ).on( "click", ".member-directory-pagination a", function() {
		event.preventDefault()
		var paged = $(this).attr("data-page");
		getallmembers(paged);
	});
	
	$('input[name="filter[]"]').change(function () {
		getallmembers();
	});
	
	$( document ).on( 'focus', '.member-search-form__input', function() {
		$( '.moc_members_count_value_div' ).hide();
		$( '.number_of_search' ).html('');
		$( '.moc_jobs_search_keyword' ).html('');
	} );
});


function getallmembers(paged){
	var search_term = jQuery("#member_s").val();
	if(search_term) {
		jQuery('.moc_jobs_search_keyword').html(search_term);
		jQuery('#member_s').val('');
		jQuery('#member_s').attr('placeholder', '');
	}
	
	//var category = jQuery("input[name='category[]']:checked").val();
	
	var category_array = [];
	jQuery("input[name='category[]']:checked").each(function(i){
		category_array.push( jQuery(this).val() );
	});
	
	var experience_array = [];
	jQuery("input[name='experience[]']:checked").each(function(i){
		experience_array.push( jQuery(this).val() );
	});
	
	var experience_years_array = [];
	jQuery("input[name='experience_years[]']:checked").each(function(i){
		experience_years_array.push( jQuery(this).val() );
	});
	
	var roles_array = [];
	jQuery("input[name='role_level[]']:checked").each(function(i){
		roles_array.push( jQuery(this).val() );
	});
	
	var skills_array = [];
	jQuery("input[name='skills[]']:checked").each(function(i){
		skills_array.push( jQuery(this).val() );
	});
	
	jQuery("input[name='filter[]']:checked").each(function(i){
		var value = jQuery(this).attr("data-value");
		var type = jQuery(this).attr("data-type");
		
		if(type == "category"){
			category_array.push( jQuery(this).val() );
		}
		if(type == "experience"){
			experience_array.push( jQuery(this).val() );
		}
		if(type == "experience_years"){
			experience_years_array.push( jQuery(this).val() );
		}
		if(type == "role_level"){
			roles_array.push( jQuery(this).val() );
		}
		if(type == "skills"){
			skills_array.push( jQuery(this).val() );
		}
	});
		
	if(paged>1){paged = paged;} else{paged=1;}
	jQuery.ajax({
		type: "POST",
		dataType: 'json',
		url: pp_ajax_form.ajaxurl,
		data: {
			'action': 'members_ajax_call',
			'search_term': search_term,
			'category': category_array,
			'experience': experience_array,
			'experience_years': experience_years_array,
			'roles': roles_array,
			'skills': skills_array,
			'paged': paged,
		},
		success: function(response){ 
			if(response.success == 'success'){
				var totalusers;
				jQuery('.members_directory').html(response.result);
				if(response.total_users > 1){
					totalusers = response.total_users+' results found';
				} else if(response.total_users == 1){
					totalusers = response.total_users+' result found';
				} else {
					totalusers = 'No result found';	
				}
				
				jQuery( '.moc_members_count_value_div' ).show();
				jQuery('.number_of_search').html(totalusers);
				
			} else {
				
			}
		}
	});
}
