var LtiPlatformText = '';
var LtiPlatformProps = null;

(function (wp) {
  var LtiPlatformIcon = wp.element.createElement(wp.primitives.SVG, {
    xmlns: "http://www.w3.org/2000/svg",
    viewBox: "0 0 24 24"
  }, wp.element.createElement(wp.primitives.Path, {
    d: "M6 14H4V6h2V4H2v12h4M7.1 17h2.1l3.7-14h-2.1M14 4v2h2v8h-2v2h4V4"
  }));

  var LtiPlatformButton = function (props) {
    return wp.element.createElement(
            wp.blockEditor.RichTextToolbarButton, {
              icon: LtiPlatformIcon,
              title: 'LTI tool',
              onClick: function () {
                if (typeof props.value.start === 'undefined') {
                  props.value.start = props.value.text.length;
                  props.value.end = props.value.text.length;
                }
                LtiPlatformText = '';
                if (props.value.end > props.value.start) {
                  LtiPlatformText = props.value.text.substr(props.value.start, props.value.end - props.value.start);
                }
                LtiPlatformProps = props;
                jQuery('.lti-platform-modal').addClass("active");
              },
            }
    );
  }
  wp.richText.registerFormatType(
          'lti-platform-format/insert-tool', {
            title: 'LTI tool',
            tagName: 'ltiplatformtool',
            className: null,
            edit: LtiPlatformButton,
          }
  );
})(window.wp);

(function ($) {
  $(document).ready(function () {
    function deeplink(){
      var urlParams = new URLSearchParams(window.location.search);
      window.open('../?lti-platform&deeplink&post=' + encodeURIComponent(urlParams.get('post')) + '&tool=' + encodeURIComponent($("input[name='tool']:checked").val()), '_blank', 'width=1000,height=800');
      $('.lti-platform-modal').removeClass('active');
    }
    $.get('../?lti-platform&tools', function (response) {
      $('#wpwrap').append(response);
      $('.lti-platform-tool').on('change', function () {
        $('#lti-platform-select').prop('disabled', false);
      });
      $('#lti-platform-select').on('click', function () {
        $.get('../?lti-platform&usecontentitem&tool=' + encodeURIComponent($("input[name='tool']:checked").val()), function (response) {
          if (response.useContentItem) {
            deeplink();
          } else {
            if (!window.LtiPlatformText) {
              window.LtiPlatformText = $('input[name="tool"]:checked').attr('toolname');
            }
            if($("#preview_lit_connections").length){
              deeplink();
            }
            var id = Math.random().toString(16).substr(2, 8);
            window.LtiPlatformProps.onChange(window.wp.richText.insert(window.LtiPlatformProps.value, '[lti-platform tool=' + $("input[name='tool']:checked").val() + ' id=' + id + ']' + window.LtiPlatformText + '[/lti-platform]'));
            window.LtiPlatformProps.onFocus();
            $('.lti-platform-modal').removeClass('active');
          }
        });
      });
      $('#lti-platform-cancel').on('click', function () {
        $('.lti-platform-modal').removeClass('active');
        $('#postdivrich').addClass('wp-editor-expand');
      });

      $('#preview_lit_connections').on('click', function () {
        jQuery('.lti-platform-modal').addClass("active");
        $('#postdivrich').removeClass('wp-editor-expand');
      });

    $(".tool-input-tr").on('click', function () {
        $(this).find('td input[type=radio]').prop('checked', true);
        $('#lti-platform-select').prop("disabled", false); 
      });

      $(".course_remove_lesson").on('click', function () {
       var deletedLessons = $("#course_removed_lessons").val();
       $(this).parent().fadeOut();
       if(deletedLessons){
        $("#course_removed_lessons").val(deletedLessons + "," + $(this).attr('lesson_id'));
       }else{
        $("#course_removed_lessons").val($(this).attr('lesson_id'));
       }
      });

    });
  });
})(jQuery);
