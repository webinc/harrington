$(document).ready(function(){
    
    if($('body').hasClass('logged-in') == false){
        $('#h-login').load('login.html');
        $('#h-register').load('register.html');
        setValidatePasswords();        
    }

    //check for login errors - this section is used to display notifications under the header bar:
    $.urlParam = function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.search);    
        return (results !== null) ? results[1] || 0 : false;
    }

    if(($.urlParam('err') * 1) == 1){
        $('#loginMessage').addClass('alert-danger');
        $('#loginMessage').html('<span> There was an error with your login. Please <a href="#h-login">try again</a>.</span>');
        $('#loginMessage').removeClass('noheight');
    }
    if($.urlParam('un') !== false){
        var username = $.urlParam('un');
        $('#loginMessage').addClass('alert-success');
        $('#loginMessage').html('<span>Welcome ' + username + '. You are logged in!</span>');    
        $('#loginMessage').removeClass('noheight');    
    }
    if(($.urlParam('logout') * 1) == 1){
        $('#loginMessage').addClass('alert-info');
        $('#loginMessage').html('<span>Thank you for your session. You are now logged out. <a href="#h-login">Login</a></span>');    
        $('#loginMessage').removeClass('noheight');    
    }    
    //end of notifications section

    if(document.getElementById('carousel-series')){
        //get carousels for homepage
        setCarouselsWithAccordions();
        fixTitleHeights();
        pickFirstProp();        
    }
    if(document.getElementById('residence_buttons')){
        //get interactive view buttons
        getInteractionViewButtons();       
    }


    //enable the scrolling login link
    $(".sliding-link").click(function(e) {
        e.preventDefault();
        var aid = $(this).attr("href");
        $('html,body').animate({scrollTop: $(aid).offset().top},'slow');
    });

    //this deploys the Matterport SDK using our free api Key for sample 3D interactions
    var mpkey = 'h05e6zm4023a5cfen4yya6pyd';
    if(document.getElementById('showcase')){
        var showcase = document.getElementById('showcase');
        showcase.addEventListener('load', async function() {
            let sdk;
            try {
                sdk = await showcase.contentWindow.MP_SDK.connect(showcase, mpkey, '3.9');
            }
            catch(e) {
                console.error(e);
                return;
            }
            console.log('Hello Bundle SDK', sdk);
        });
    }

    //populate call_to_action with its relevant button
    //getting the target and the text from the parent element
    if(document.getElementById('call_to_action')){
        var bt = document.createElement('button');
        var a = document.createElement('a');
        var link = $('#call_to_action').data('target');
        a.setAttribute('href', link );
        bt.innerHTML = $('#call_to_action').data('cta');
        a.append(bt);
        $('#call_to_action').append(a);
    }

    //go to the correct property if it is selected by the previous page
    findCTALocation();

    //fill un and pw after successful registration:
    if(document.getElementById('h-login')){
        var un = $('#h-login').data('un');
        var pw = $('#h-login').data('pw');
        $('#log-username').val(un);
        $('#log-password').val(pw);
    }

});    

// create an array for use in the location buttons
var locations = ['Harrington','Cornwall','Southwell','Mews','Kensington','Tourist','Apartments'];


function pickFirstProp(){
    setTimeout(function() { 
        $('.h_accordion_buttons div:first-child').trigger('click');
    }, 1000);
}


function setValidatePasswords(){
    $(document).on('change', '.pw-check', function(){
        var presscheck = true;
        $('.pw-check').each(function(){
            if($(this).val() == ""){
                //do not validate if either pw box is empty
                presscheck = false;
            }
        });
        if(presscheck){
            if($('#reg-password').val() !== $('#reg-confirm-password').val()){
                $('.pw-check').removeClass('right-pw');
                $('.pw-check').addClass('wrong-pw');
                alert('Passwords do not match. Please check');
            } else {
                $('.pw-check').removeClass('wrong-pw');
                $('.pw-check').addClass('right-pw'); 
            }            
        }
    });
}

function getInteractionViewButtons(){
    var ib = document.createElement('div');
    ib.classList.add('h_accordion_buttons');
    //these are the matterport sample showcases - 1 for each location
    var showcases = ['oyaicKWaEQw','iL4RdJqi2yK','VJA2c2fMgKu','wPjc6kxkhcF','ELpd2xuJSHq','pxzSigb4rRt','PzFyUcEsFxs'];
    $.each(locations, function(loc){
        var title = document.createElement('div');
        title.classList.add('h_button');
        title.setAttribute('data-target', showcases[loc]);
        title.innerHTML = locations[loc];
        $('#residence_buttons').append(title);
    }); 
    //set the title button behaviour to show/hide showcases
    $('.h_button').click(function(){
        $('.h_button').removeClass('active');
        var cid = jQuery(this).data('target');
        $(this).addClass('active');
        var isrce = 'showcase-bundle/showcase.html?m=' + cid;
        $('#showcase').attr('src',isrce);
        setCTALink();
    });
}

function setCarouselsWithAccordions(){
    var l = locations.length + 1;
    var imagesPerTitle = 5;
    var totalImages = imagesPerTitle * l;
    var images = {data : []};
    $.ajax({
        url: 'https://jsonplaceholder.typicode.com/photos',
        async: false,
        dataType: 'json',
        success: function (json) {
            //lets not get more image urls than we need
            for(a = 0; a < totalImages; a++){
                images.data.push(json[a]);
            }          
        }
    });    
    //for the accordion buttons...
    var acc = document.createElement('div');
    acc.classList.add('h_accordion_buttons');
    //for all carousel content
    var cacc = document.createElement('div');
    cacc.classList.add('h_carousels');   
    var i = 1; thisImg = 0;
    $.each(locations, function(loc){
        var title = document.createElement('div');
        title.classList.add('h_button');
        title.setAttribute('data-target', 'owl_' + i);
        title.innerHTML = locations[loc];
        //title.setAttribute('onclick',"show_h('" + locId + "')");
        acc.append(title);
        var carousel = document.createElement('div');
        carousel.classList.add('owl-carousel', 'owl-theme', 'collapse');
        carousel.setAttribute('aria-expanded', 'false');        
        for(k = 0; k < 5; k ++){
            var carouselContent = getCarouselContent(thisImg, images);
            carousel.append(carouselContent);
            thisImg ++;
        }
        carousel.setAttribute('id', 'owl_' + i);
        cacc.append(carousel);
        i++;
    });

    $('#carousel-series').append(acc);
    $('#carousel-series').append(cacc);

    //set all carousels with owlCarousel properties:
    for(j = 1; j < i; j++){
        $('#owl_' + j).owlCarousel({
            navigation : true, // Show next and prev buttons
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem: true        
        });        
    }
    
    //set the title button behaviour to show/hide carousels
    $('.h_button').click(function(){
        $('.h_button').removeClass('active');
        var cid = jQuery(this).data('target');
        $(this).addClass('active');
        $('.owl-carousel').each(function(){
            if($(this).attr('id') == cid){
                $(this).removeClass('collapse');
                $(this).addClass('show');
                $(this).attr('aria-expanded', 'true');        
            } else {
                $(this).addClass('collapse');  
                $(this).removeClass('show');
                $(this).attr('aria-expanded', 'false');        
            }
        });
        setCTALink();
    });       
}

function setCTALink(){
    //updates the CTA Link, if it has class 'contextual' so that the page it 
    // goes to relates to the currently selected location:
    if($('#call_to_action').hasClass('contextual')){
        var link = $('#call_to_action').data('target');
        $('.h_button').each(function(){
                if($(this).hasClass('active')){
                    link = link + '?loc=' + $(this).text();
                }
        });
        $('#call_to_action a').attr('href', link);
    }
}

function findCTALocation(){
    //sets the correct location from one page to the next
    if($('body').hasClass('haslocation')){
        var done = 0;
        var loc = $('body').data('location');
        $('.h_button').each(function(){
            if($(this).text() == loc){
                $(this).trigger('click');
                done = 1;
            }
        });
        if(done == 0){
            //fall back to ensure a property is chosen and the content isn't blank.
            $('.h_button:first').trigger('click');      
        }
    } else {
    //click the first button to display its showcase and make it active.
        $('.h_button:first').trigger('click');      
    }
}

function getCarouselContent(thisimg, images){
    var hld = document.createElement('div');
    hld.classList.add('single-slide');
    var img = document.createElement('img');
    var title = document.createElement('div');
    title.classList.add('slide-title');
    title.innerHTML = images.data[thisimg].title;
    img.setAttribute('src', images.data[thisimg].url);
    hld.append(img);
    hld.append(title);
    return hld;
}

function fixTitleHeights(){
    jQuery('.owl-carousel').each(function(){
        var ht = -1;
        jQuery(this).find('.slide-title').each(function(){
            ht = ht > $(this).height() ? ht : $(this).height();
        });
        jQuery(this).find('.slide-title').each(function(){
            jQuery(this).height(ht);
        });
    });
}
