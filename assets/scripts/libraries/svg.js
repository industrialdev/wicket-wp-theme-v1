var mediaGridObserver = new MutationObserver(function (mutations) {

    for (var i = 0; i < mutations.length; i++)
    {

        for (var j = 0; j < mutations[i].addedNodes.length; j++)
        {
            element = jQuery(mutations[i].addedNodes[j]);

            if (element.attr('class'))
            {
                elementClass = element.attr('class');
                if (element.attr('class').indexOf('attachment') != -1)
                {

                    attachmentPreview = element.children('.attachment-preview');
                    if (attachmentPreview.length != 0)
                    {
                        if (attachmentPreview.attr('class').indexOf('subtype-svg+xml') != -1)
                        {
                            var handler = function (element) {

                                jQuery.ajax({

                                    url: script_vars.AJAXurl,
                                    type: "POST",
                                    dataType: 'html',
                                    data: {
                                        'action': 'svg_get_attachment_url',
                                        'attachmentID': element.attr('data-id')
                                    },
                                    success: function (data) {
                                        if (data)
                                        {
                                            element.find('img').attr('src', data);
                                            element.find('.filename').text('SVG Image');
                                        }
                                    }
                                });

                            }(element);

                        }
                    }
                }
            }
        }
    }
});

var attachmentPreviewObserver = new MutationObserver(function (mutations) {
    for (var i = 0; i < mutations.length; i++)
    {
        for (var j = 0; j < mutations[i].addedNodes.length; j++)
        {
            var element = jQuery(mutations[i].addedNodes[j]);
            var onAttachmentPage = false;
            if ((element.hasClass('attachment-details')) || element.find('.attachment-details').length != 0)
            {
                onAttachmentPage = true;
            }

            if (onAttachmentPage == true)
            {
                var urlLabel = element.find('label[data-setting="url"]');
                if (urlLabel.length != 0)
                {
                    var value = urlLabel.find('input').val();
                    element.find('.details-image').attr('src', value);
                }
            }
        }
    }
});

document.addEventListener("DOMContentLoaded", function() {
    mediaGridObserver.observe(document.body, {
        childList: true,
        subtree: true
    });

    attachmentPreviewObserver.observe(document.body, {
        childList: true,
        subtree: true
    });
});


