
<?php global $post; ?>
<!-- make html form that take 'sort' as input field and submit using jQuery.ajax -->
<form id="trek-settings-form">
   <label for="sort">Sort</label>
   <br />
   <input type="number" name="sort" id="sort" value="<?php echo intval(get_post_meta($post->ID, 'sort', true)); ?>">
   <br />
   <br />
   <input type="button" id="save-trek-settings-button" value="Save">
</form>

<!-- JQuery script to submit 'trek-settings-form' using ajax -->
<script type="text/javascript">
   jQuery(document).ready(function($) {
    
      $('#save-trek-settings-button').on("click", function(e) {
        e.preventDefault();
        var sort = $('#sort').val();
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'trek_settings',
                sort: sort,
                post_id: <?php echo $post->ID; ?>
            },
            success: function(response) {
                console.log(response);
            }
        });
      });
   });
</script>