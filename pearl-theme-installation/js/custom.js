$(document).ready(function () {
    var navListItems = $('div.setup-panel div a'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            skipCheckbox = $("#skip-" + curStepBtn);
            isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }


        if ((skipCheckbox).prop('checked') == true) {
            isValid = true;
            nextStepWizard.removeAttr('disabled').trigger('click');
        } else {
            if (isValid) {
                var $this = $(this);
                $this.button('loading');
                var ajaxRequest = curStepBtn + '.php';
                var ajaxParams = {};

                switch (curStepBtn) {
                    case 'step-2':
                        curInputs = curStep.find("input[type='checkbox']");
                        if (curInputs.length) {
                            var modulesEnable = [];
                            var modulesDisable = [];
                            for (var i = 0; i < curInputs.length; i++) {
                                if (!$(curInputs[i]).prop('checked')) {
                                    var inputVal = $(curInputs[i]).val();
                                    modulesDisable.push(inputVal);
                                } else {
                                    var inputVal = $(curInputs[i]).val();
                                    modulesEnable.push(inputVal);
                                }
                            }
                        }
                        ajaxParams.modulesEnable = modulesEnable;
                        ajaxParams.modulesDisable = modulesDisable;
                        break;
                    case 'step-4':
                        ajaxParams.storeCode = $('#theme-activation-store-code').val();
                        break;
                    case 'step-5':
                        ajaxParams.storeCode = $('#demo-configuration-store-code').val();
                        ajaxParams.demoVersion = $('#demo-configuration-version').val();
                        break;
                    case 'step-6':
                        ajaxParams.storeCode = $('#theme-configuration-store-code').val();
                        ajaxParams.homePage = $('#theme-configuration-home-page').val();
                        ajaxParams.header = $('#theme-configuration-header').val();
                        ajaxParams.storeCode = $('#theme-configuration-store-code').val();
                        ajaxParams.categoryColumns = $('#theme-configuration-category-columns').val();
                        ajaxParams.productVersion = $('#theme-configuration-product-page').val();
                        ajaxParams.preFooter = $('#theme-configuration-prefooter').val();
                        ajaxParams.footer = $('#theme-configuration-footer').val();
                        break;
                    case 'step-7':
                        ajaxParams.deleteInstaller = $('#delete-installer').val();
                }

                $.ajax({
                    type: "POST",
                    url: ajaxRequest,
                    dataType: 'json',
                    data: ajaxParams,
                    success: function(data) {
                        var responseMsg = data.msg;
                        if (ajaxRequest === 'step-3.php' && !data.error) {
                            // make a second request to generate less files
                            $.ajax({
                                type: "POST",
                                url: 'step-3.1.php',
                                dataType: 'json',
                                data: ajaxParams,
                                success: function (data) {
                                    $this.button('reset');
                                    responseMsg += data.msg;
                                    if (!data.error) {
                                        nextStepWizard.removeAttr('disabled').trigger('click');
                                        $('.result-container').append("<p class='success'>" + curStepBtn.toUpperCase() + ": <br/> " + responseMsg + "</p>");
                                    } else {
                                        $('.result-container').append("<p class='error'>" + curStepBtn.toUpperCase() + ": <br/> "  + responseMsg + "</p>");
                                    }
                                },
                                error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    $('.result-container').append("<p class='error'>" + curStepBtn.toUpperCase() + ": <br/> " + " Server request error: " + errorThrown + "</p>");
                                    $this.button('reset');
                                }
                            });
                        } else {
                            $this.button('reset');
                            if (!data.error) {
                                nextStepWizard.removeAttr('disabled').trigger('click');
                                $('.result-container').append("<p class='success'>" + curStepBtn.toUpperCase() + ": <br/> " + data.msg + "</p>");

                                if (typeof data.modules != 'undefined' && data.modules.length) {
                                    $('#modules_list').html('');
                                    data.modules.forEach(function(el) {
                                        var checked = el.active == '1' || el.isNew == '1' ? 'checked="checked"' : '';
                                        var disabled = el.selectable == '1' ? 'disabled="disabled"' : '';
                                        var required = el.selectable == '1' ? ' (mandatory) ' : '';
                                        var requiredClass = el.selectable == '1' ? ' mandatory' : '';
                                        var isNew = el.isNew == '1' && data.allNew != '1' ? '(new)' : '';
                                        var isNewClass = el.isNew == '1' && data.allNew != '1' ? 'new-module' : '';
                                        var html ='<li clss="form-group">';
                                        html +='<fieldset>';
                                        html += '<input id="' + el.value.toLocaleLowerCase() + '" type="checkbox" name="' + el.value.toLocaleLowerCase() + '" value="' + el.value + '" ' + checked + ' ' + disabled + ' />';
                                        html += '<label class="' + isNewClass + requiredClass + '" for="' + el.value.toLocaleLowerCase() + '">' + el.name + ' ' + required + isNew + '</label>';
                                        html += '</fieldset>';
                                        html += '</li>';

                                        $('#modules_list').append(html);
                                    });
                                }
                            } else {
                                $('.result-container').append("<p class='error'>" + curStepBtn.toUpperCase() + ": <br/> "  + data.msg + "</p>");
                            }
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $('.result-container').append("<p class='error'>" + curStepBtn.toUpperCase() + ": <br/> " + " Server request error: " + errorThrown + "</p>");
                        $this.button('reset');
                    }
                });
            }
        }

    });

    $('div.setup-panel div a.btn-primary').trigger('click');

    /** preview update */
    $('.form-control').on('change', function() {
        var clickedId = $(this).attr('id'),
            clickedVal = $(this).val(),
            previewSection = '';

        switch (clickedId) {
            case 'theme-configuration-home-page':
            case 'theme-configuration-category-columns':
            case 'theme-configuration-product-page':
                previewSection = $('.page-preview');
                break;
            default:
                previewSection = $('.' + clickedId);
                break;
        }

        if (previewSection.length) {
            var imgExt = '.jpg';
            if (clickedId == 'theme-configuration-header' && clickedVal == 'v2') imgExt = '.png';
            if (clickedId == 'theme-configuration-home-page' && clickedVal == 'v6') {
                var imageSrc = [
                    'img/' + clickedId + '/' + clickedVal + '-1' + imgExt,
                    'img/' + clickedId + '/' + clickedVal + '-2' + imgExt,
                    'img/' + clickedId + '/' + clickedVal + '-3' + imgExt
                ];
            } else {
                var imageSrc = ['img/' + clickedId + '/' + clickedVal + imgExt];
            }

            var previewImg = '';
            for (var i = 0; i < imageSrc.length; i++) {
                previewImg += '<img alt="" class="' + clickedId + '-' + clickedVal + '" src="' + imageSrc[i] + '" />'
            }
            previewSection.html(previewImg);

            if (
                clickedId == 'theme-configuration-header' ||
                clickedId == 'theme-configuration-prefooter' ||
                clickedId == 'theme-configuration-footer'
            ) {
                var scrollPos = previewSection.position().top;
                setTimeout(function() {
                    $('.preview-images').animate({
                        scrollTop: scrollPos
                    }, 600);
                }, 500);
            }
        }
    });

    /** remove notification message */
    $('.btn-proc-1').on('click', function() {
       $('.notification-msg').hide();
    });
    $('.step-1-link').on('click', function() {
        $('.notification-msg').show();
    });

});
