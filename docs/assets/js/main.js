$(document).ready(function() {
	
	/* ===== Affix Sidebar ===== */
	/* Ref: http://getbootstrap.com/javascript/#affix-examples */

    	
	$('#doc-menu').affix({
        offset: {
            top: ($('#header').outerHeight(true) + $('#doc-header').outerHeight(true)) + 45,
            bottom: ($('#footer').outerHeight(true) + $('#promo-block').outerHeight(true)) + 75
        }
    });
    
    /* Hack related to: https://github.com/twbs/bootstrap/issues/10236 */
    $(window).on('load resize', function() {
        $(window).trigger('scroll'); 
    });

    /* Activate scrollspy menu */
    $('body').scrollspy({target: '#doc-nav', offset: 100});
    
    /* Smooth scrolling */
	$('a.scrollto').on('click', function(e){
        //store hash
        var target = this.hash;    
        e.preventDefault();
		$('body').scrollTo(target, 800, {offset: 0, 'axis':'y'});
		
	});
	
    
    /* ======= jQuery Responsive equal heights plugin ======= */
    /* Ref: https://github.com/liabru/jquery-match-height */
    
     $('#cards-wrapper .item-inner').matchHeight();
     $('#showcase .card').matchHeight();
     
    /* Bootstrap lightbox */
    /* Ref: http://ashleydw.github.io/lightbox/ */

    $(document).delegate('*[data-toggle="lightbox"]', 'click', function(e) {
        e.preventDefault();
        $(this).ekkoLightbox();
    });    


});


var SP = SP || {};
SP = {
    structure:{
        init:function(){
            $(document).ready(function(){
                $('.ps-item').on('click',function(){
                    SP.structure.showInfo($(this).attr('data-for'));
                });
            });
        },
        showInfo:function(infoFor){
            $('.infos').hide();
            $('#'+infoFor).show();
        }
    },
    accordian:{
        init:function(){
            $(document).ready(function () {
               $('.slideLeft').click(function () {
                   SP.accordian.toggle(this);
               });
            });
        },
        toggle:function(elem){
            $('.slideContent').slideUp();
            $('.slideContent-'+$(elem).attr('data-count')).slideToggle();
        },
    },
};