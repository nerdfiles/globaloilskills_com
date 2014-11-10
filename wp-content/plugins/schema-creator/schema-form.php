<?php
// don't load form on non-editing pages
$current_screen = get_current_screen();
if ( 'post' !== $current_screen->base )
  return;

// don't display form for users who don't have access
if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
return;

?>

<script type="text/javascript">
  function InsertSchema() {
    //select field options
    var type			= jQuery('#schema_builder select#schema_type').val();
    var evtype			= jQuery('#schema_builder select#schema_evtype').val();
    var orgtype			= jQuery('#schema_builder select#schema_orgtype').val();
    var country			= jQuery('#schema_builder select#schema_country').val();
    var condition		= jQuery('#schema_builder select#schema_condition').val();

    //text field options
    var name			= jQuery('#schema_builder input#schema_name').val();
    var orgname			= jQuery('#schema_builder input#schema_orgname').val();
    var jobtitle		= jQuery('#schema_builder input#schema_jobtitle').val();
    var url				= jQuery('#schema_builder input#schema_url').val();
    var bday			= jQuery('#schema_builder input#schema_bday-format').val();
    var street			= jQuery('#schema_builder input#schema_street').val();
    var pobox			= jQuery('#schema_builder input#schema_pobox').val();
    var city			= jQuery('#schema_builder input#schema_city').val();
    var state			= jQuery('#schema_builder input#schema_state').val();
    var postalcode		= jQuery('#schema_builder input#schema_postalcode').val();
    var email			= jQuery('#schema_builder input#schema_email').val();
    var phone			= jQuery('#schema_builder input#schema_phone').val();
    var fax				= jQuery('#schema_builder input#schema_fax').val();
    var brand			= jQuery('#schema_builder input#schema_brand').val();
    var manfu			= jQuery('#schema_builder input#schema_manfu').val();

    var hiring_organization			= jQuery('#schema_builder input#schema_hiring_organization').val();
    var skills			= jQuery('#schema_builder input#schema_skills').val();
    var industry			= jQuery('#schema_builder input#schema_industry').val();

    var model			= jQuery('#schema_builder input#schema_model').val();
    var prod_id			= jQuery('#schema_builder input#schema_prod_id').val();
    var single_rating	= jQuery('#schema_builder input#schema_single_rating').val();
    var agg_rating		= jQuery('#schema_builder input#schema_agg_rating').val();
    var price			= jQuery('#schema_builder input#schema_price').val();
    var sdate			= jQuery('#schema_builder input#schema_sdate-format').val();
    var stime			= jQuery('#schema_builder input#schema_stime').val();
    var edate			= jQuery('#schema_builder input#schema_edate-format').val();
    var duration		= jQuery('#schema_builder input#schema_duration').val();
    var actor_group		= jQuery('#schema_builder input#schema_actor_1').val();
    var director		= jQuery('#schema_builder input#schema_director').val();
    var producer		= jQuery('#schema_builder input#schema_producer').val();
    var author			= jQuery('#schema_builder input#schema_author').val();
    var publisher		= jQuery('#schema_builder input#schema_publisher').val();
    var edition			= jQuery('#schema_builder input#schema_edition').val();
    var isbn			= jQuery('#schema_builder input#schema_isbn').val();
    var pubdate			= jQuery('#schema_builder input#schema_pubdate-format').val();
    var ebook			= jQuery('#schema_builder input#schema_ebook').is(':checked');
    var paperback		= jQuery('#schema_builder input#schema_paperback').is(':checked');
    var hardcover		= jQuery('#schema_builder input#schema_hardcover').is(':checked');
    var rev_name		= jQuery('#schema_builder input#schema_rev_name').val();
    var user_review		= jQuery('#schema_builder input#schema_user_review').val();
    var min_review		= jQuery('#schema_builder input#schema_min_review').val();
    var max_review		= jQuery('#schema_builder input#schema_max_review').val();
    var ingrt_group		= jQuery('#schema_builder input#schema_ingrt_1').val();
    var image			= jQuery('#schema_builder input#schema_image').val();
    var prephours		= jQuery('#schema_builder input#schema_prep_hours').val();
    var prepmins		= jQuery('#schema_builder input#schema_prep_mins').val();
    var cookhours		= jQuery('#schema_builder input#schema_cook_hours').val();
    var cookmins		= jQuery('#schema_builder input#schema_cook_mins').val();
    var yield			= jQuery('#schema_builder input#schema_yield').val();
    var calories		= jQuery('#schema_builder input#schema_calories').val();
    var fatcount		= jQuery('#schema_builder input#schema_fatcount').val();
    var sugarcount		= jQuery('#schema_builder input#schema_sugarcount').val();
    var saltcount		= jQuery('#schema_builder input#schema_saltcount').val();

    // textfield options
    var description		= jQuery('#schema_builder textarea#schema_description').val();
    var rev_body		= jQuery('#schema_builder textarea#schema_rev_body').val();
    var instructions	= jQuery('#schema_builder textarea#schema_instructions').val();

    // output setups
    output = '[schema ';
    output += 'type="' + type + '" ';

    // person
    if(type == 'person' ) {
      if(name)
        output += 'name="' + name + '" ';
      if(orgname)
        output += 'orgname="' + orgname + '" ';
      if(jobtitle)
        output += 'jobtitle="' + jobtitle + '" ';
      if(url)
        output += 'url="' + url + '" ';
      if(description)
        output += 'description="' + description + '" ';
      if(bday)
        output += 'bday="' + bday + '" ';
      if(street)
        output += 'street="' + street + '" ';
      if(pobox)
        output += 'pobox="' + pobox + '" ';
      if(city)
        output += 'city="' + city + '" ';
      if(state)
        output += 'state="' + state + '" ';
      if(postalcode)
        output += 'postalcode="' + postalcode + '" ';
      if(country && country !== 'none')
        output += 'country="' + country + '" ';
      if(email)
        output += 'email="' + email + '" ';
      if(phone)
        output += 'phone="' + phone + '" ';
    }

    // product
    if(type == 'product' ) {
      if(url)
        output += 'url="' + url + '" ';
      if(name)
        output += 'name="' + name + '" ';
      if(description)
        output += 'description="' + description + '" ';
      if(brand)
        output += 'brand="' + brand + '" ';
      if(manfu)
        output += 'manfu="' + manfu + '" ';
      if(model)
        output += 'model="' + model + '" ';
      if(prod_id)
        output += 'prod_id="' + prod_id + '" ';
      if(single_rating)
        output += 'single_rating="' + single_rating + '" ';
      if(agg_rating)
        output += 'agg_rating="' + agg_rating + '" ';
      if(price)
        output += 'price="' + price + '" ';
      if(condition && condition !=='none')
        output += 'condition="' + condition + '" ';
    }

    // jobposting
    if(type == 'job_posting' ) {
      if(url)
        output += 'url="' + url + '" ';
      if(name)
        output += 'name="' + name + '" ';
      if(description)
        output += 'description="' + description + '" ';
      if(hiring_organization)
        output += 'hiring_organization="' + hiring_organization + '" ';
      if(skills)
        output += 'skills="' + industry + '" ';
      if(industry)
        output += 'industry="' + industry + '" ';
    }

    // event
    if(type == 'event' ) {
      if(evtype && evtype !== 'none')
        output += 'evtype="' + evtype + '" ';
      if(url)
        output += 'url="' + url + '" ';
      if(name)
        output += 'name="' + name + '" ';
      if(description)
        output += 'description="' + description + '" ';
      if(sdate)
        output += 'sdate="' + sdate + '" ';
      if(stime)
        output += 'stime="' + stime + '" ';
      if(edate)
        output += 'edate="' + edate + '" ';
      if(duration)
        output += 'duration="' + duration + '" ';
      if(street)
        output += 'street="' + street + '" ';
      if(pobox)
        output += 'pobox="' + pobox + '" ';
      if(city)
        output += 'city="' + city + '" ';
      if(state)
        output += 'state="' + state + '" ';
      if(postalcode)
        output += 'postalcode="' + postalcode + '" ';
      if(country && country !== 'none')
        output += 'country="' + country + '" ';
    }

    // organization
    if(type == 'organization' ) {
      if(orgtype)
        output += 'orgtype="' + orgtype + '" ';
      if(url)
        output += 'url="' + url + '" ';
      if(name)
        output += 'name="' + name + '" ';
      if(description)
        output += 'description="' + description + '" ';
      if(street)
        output += 'street="' + street + '" ';
      if(pobox)
        output += 'pobox="' + pobox + '" ';
      if(city)
        output += 'city="' + city + '" ';
      if(state)
        output += 'state="' + state + '" ';
      if(postalcode)
        output += 'postalcode="' + postalcode + '" ';
      if(country && country !== 'none')
        output += 'country="' + country + '" ';
      if(email)
        output += 'email="' + email + '" ';
      if(phone)
        output += 'phone="' + phone + '" ';
      if(fax)
        output += 'fax="' + fax + '" ';
    }

    // movie
    if(type == 'movie' ) {
      if(url)
        output += 'url="' + url + '" ';
      if(name)
        output += 'name="' + name + '" ';
      if(description)
        output += 'description="' + description + '" ';
      if(director)
        output += 'director="' + director + '" ';
      if(producer)
        output += 'producer="' + producer + '" ';
      if(actor_group) {
        var count = 0;
        jQuery('div.sc_actor').each(function(){
          count++;
          var actor = jQuery(this).find('input').val();
          output += 'actor_' + count + '="' + actor + '" ';
        });
      }
    }

    // book
    if(type == 'book' ) {
      if(url)
        output += 'url="' + url + '" ';
      if(name)
        output += 'name="' + name + '" ';
      if(description)
        output += 'description="' + description + '" ';
      if(author)
        output += 'author="' + author + '" ';
      if(publisher)
        output += 'publisher="' + publisher + '" ';
      if(pubdate)
        output += 'pubdate="' + pubdate + '" ';
      if(edition)
        output += 'edition="' + edition + '" ';
      if(isbn)
        output += 'isbn="' + isbn + '" ';
      if(ebook === true )
        output += 'ebook="yes" ';
      if(paperback === true )
        output += 'paperback="yes" ';
      if(hardcover === true )
        output += 'hardcover="yes" ';
    }

    // review
    if(type == 'review' ) {
      if(url)
        output += 'url="' + url + '" ';
      if(name)
        output += 'name="' + name + '" ';
      if(description)
        output += 'description="' + description + '" ';
      if(rev_name)
        output += 'rev_name="' + rev_name + '" ';
      if(rev_body)
        output += 'rev_body="' + rev_body + '" ';
      if(author)
        output += 'author="' + author + '" ';
      if(pubdate)
        output += 'pubdate="' + pubdate + '" ';
      if(user_review)
        output += 'user_review="' + user_review + '" ';
      if(min_review)
        output += 'min_review="' + min_review + '" ';
      if(max_review)
        output += 'max_review="' + max_review + '" ';
    }

    // recipe
    if(type == 'recipe' ) {
      if(name)
        output += 'name="' + name + '" ';
      if(author)
        output += 'author="' + author + '" ';
      if(pubdate)
        output += 'pubdate="' + pubdate + '" ';
      if(image)
        output += 'image="' + image + '" ';
      if(description)
        output += 'description="' + description + '" ';
      if(prephours)
        output += 'prephours="' + prephours + '" ';
      if(prepmins)
        output += 'prepmins="' + prepmins + '" ';
      if(cookhours)
        output += 'cookhours="' + cookhours + '" ';
      if(cookmins)
        output += 'cookmins="' + cookmins + '" ';
      if(yield)
        output += 'yield="' + yield + '" ';
      if(calories)
        output += 'calories="' + calories + '" ';
      if(fatcount)
        output += 'fatcount="' + fatcount + '" ';
      if(sugarcount)
        output += 'sugarcount="' + sugarcount + '" ';
      if(saltcount)
        output += 'saltcount="' + saltcount + '" ';
      if(ingrt_group) {
        var count = 0;
        jQuery('div.sc_ingrt').each(function(){
          count++;
          var ingrt = jQuery(this).find('input').val();
          output += 'ingrt_' + count + '="' + ingrt + '" ';
        });
      }
      if(instructions)
        output += 'instructions="' + instructions + '" ';
    }

    output += ']';

    window.send_to_editor(output);
  }
</script>

<div id="schema_build_form" style="display:none;">
  <div id="schema_builder" class="schema_wrap">
    <!-- schema type dropdown -->
    <div id="sc_type">
      <label for="schema_type"><?php _e('Schema Type', 'schema'); ?></label>
      <select name="schema_type" id="schema_type" class="schema_drop schema_thindrop">
        <option class="holder" value="none">(<?php _e('Select A Type', 'schema'); ?>)</option>
        <option value="person"><?php _e('Person', 'schema'); ?></option>
        <!--option value="product"><?php _e('Product', 'schema'); ?></option-->
        <option value="job_posting"><?php _e('Job Posting', 'schema'); ?></option>
        <option value="event"><?php _e('Event', 'schema'); ?></option>
        <option value="organization"><?php _e('Organization', 'schema'); ?></option>
        <!--option value="movie"><?php _e('Movie', 'schema'); ?></option>
        <option value="book"><?php _e('Book', 'schema'); ?></option>
        <option value="review"><?php _e('Review', 'schema'); ?></option>
        <option value="recipe"><?php _e('Recipe', 'schema'); ?></option-->
      </select>
    </div>
    <!-- end schema type dropdown -->

    <div id="sc_evtype" class="sc_option" style="display:none">
      <label for="schema_evtype"><?php _e('Event Type', 'schema'); ?></label>
      <select name="schema_evtype" id="schema_evtype" class="schema_drop schema_thindrop">
        <option value="Event"><?php _e('General', 'schema'); ?></option>
        <option value="BusinessEvent"><?php _e('Business', 'schema'); ?></option>
        <option value="ChildrensEvent"><?php _e('Childrens', 'schema'); ?></option>
        <option value="ComedyEvent"><?php _e('Comedy', 'schema'); ?></option>
        <option value="DanceEvent"><?php _e('Dance', 'schema'); ?></option>
        <option value="EducationEvent"><?php _e('Education', 'schema'); ?></option>
        <option value="Festival"><?php _e('Festival', 'schema'); ?></option>
        <option value="FoodEvent"><?php _e('Food', 'schema'); ?></option>
        <option value="LiteraryEvent"><?php _e('Literary', 'schema'); ?></option>
        <option value="MusicEvent"><?php _e('Music', 'schema'); ?></option>
        <option value="SaleEvent"><?php _e('Sale', 'schema'); ?></option>
        <option value="SocialEvent"><?php _e('Social', 'schema'); ?></option>
        <option value="SportsEvent"><?php _e('Sports', 'schema'); ?></option>
        <option value="TheaterEvent"><?php _e('Theater', 'schema'); ?></option>
        <option value="UserInteraction"><?php _e('User Interaction', 'schema'); ?></option>
        <option value="VisualArtsEvent"><?php _e('Visual Arts', 'schema'); ?></option>
      </select>
    </div>

    <div id="sc_orgtype" class="sc_option" style="display:none">
      <label for="schema_orgtype"><?php _e('Organziation Type', 'schema'); ?></label>
      <select name="schema_orgtype" id="schema_orgtype" class="schema_drop schema_thindrop">
        <option value="Organization"><?php _e('General', 'schema'); ?></option>
        <option value="Corporation"><?php _e('Corporation', 'schema'); ?></option>
        <option value="EducationalOrganization"><?php _e('School', 'schema'); ?></option>
        <option value="GovernmentOrganization"><?php _e('Government', 'schema'); ?></option>
        <option value="LocalBusiness"><?php _e('Local Business', 'schema'); ?></option>
        <option value="NGO"><?php _e('NGO', 'schema'); ?></option>
        <option value="PerformingGroup"><?php _e('Performing Group', 'schema'); ?></option>
        <option value="SportsTeam"><?php _e('Sports Team', 'schema'); ?></option>
      </select>
    </div>

    <div id="sc_name" class="sc_option" style="display:none">
      <label for="schema_name"><?php _e('Name', 'schema'); ?></label>
      <input type="text" name="schema_name" class="form_full" value="" id="schema_name" />
    </div>

    <div id="sc_image" class="sc_option" style="display:none">
      <label for="schema_image">Image URL</label>
      <input type="text" name="schema_image" class="form_full" value="" id="schema_image" />
    </div>

    <div id="sc_orgname" class="sc_option" style="display:none">
      <label for="schema_orgname"><?php _e('Organization', 'schema'); ?></label>
      <input type="text" name="schema_orgname" class="form_full" value="" id="schema_orgname" />
    </div>

    <div id="sc_jobtitle" class="sc_option" style="display:none">
      <label for="schema_jobtitle"><?php _e('Job Title', 'schema'); ?></label>
      <input type="text" name="schema_jobtitle" class="form_full" value="" id="schema_jobtitle" />
    </div>

    <div id="sc_url" class="sc_option" style="display:none">
      <label for="schema_url"><?php _e('Website', 'schema'); ?></label>
      <input type="text" name="schema_url" class="form_full" value="" id="schema_url" />
    </div>

    <div id="sc_description" class="sc_option" style="display:none">
      <label for="schema_description"><?php _e('Description', 'schema'); ?></label>
      <textarea name="schema_description" id="schema_description"></textarea>
    </div>

    <div id="sc_rev_name" class="sc_option" style="display:none">
      <label for="schema_rev_name"><?php _e('Item Name', 'schema'); ?></label>
      <input type="text" name="schema_rev_name" class="form_full" value="" id="schema_rev_name" />
    </div>

    <div id="sc_rev_body" class="sc_option" style="display:none">
      <label for="schema_rev_body"><?php _e('Item Review', 'schema'); ?></label>
      <textarea name="schema_rev_body" id="schema_rev_body"></textarea>
    </div>

    <div id="sc_director" class="sc_option" style="display:none">
      <label for="schema_director"><?php _e('Director', 'schema'); ?></label>
      <input type="text" name="schema_director" class="form_full" value="" id="schema_director" />
    </div>

    <div id="sc_producer" class="sc_option" style="display:none">
      <label for="schema_producer"><?php _e('Producer', 'schema'); ?></label>
      <input type="text" name="schema_producer" class="form_full" value="" id="schema_producer" />
    </div>

    <div id="sc_actor_1" class="sc_option sc_actor sc_repeater" style="display:none">
      <label for="schema_actor_1"><?php _e('Actor', 'schema'); ?></label>
      <input type="text" name="schema_actor_1" class="form_full actor_input" value="" id="schema_actor_1" />
    </div>

    <input type="button" id="clone_actor" value="<?php _e('Add Another Actor', 'schema'); ?>" style="display:none;" />

    <div id="sc_sdate" class="sc_option" style="display:none">
      <label for="schema_sdate"><?php _e('Start Date', 'schema'); ?></label>
      <input type="text" id="schema_sdate" name="schema_sdate" class="schema_datepicker timepicker form_third" value="" />
      <input type="hidden" id="schema_sdate-format" class="schema_datepicker-format" value="" />
    </div>

    <div id="sc_stime" class="sc_option" style="display:none">
      <label for="schema_stime"><?php _e('Start Time', 'schema'); ?></label>
      <input type="text" id="schema_stime" name="schema_stime" class="schema_timepicker form_third" value="" />
    </div>

    <div id="sc_edate" class="sc_option" style="display:none">
      <label for="schema_edate"><?php _e('End Date', 'schema'); ?></label>
      <input type="text" id="schema_edate" name="schema_edate" class="schema_datepicker form_third" value="" />
      <input type="hidden" id="schema_edate-format" class="schema_datepicker-format" value="" />
    </div>

    <div id="sc_duration" class="sc_option" style="display:none">
      <label for="schema_duration"><?php _e('Duration', 'schema'); ?></label>
      <input type="text" id="schema_duration" name="schema_duration" class="schema_timepicker form_third" value="" />
    </div>

    <div id="sc_bday" class="sc_option" style="display:none">
      <label for="schema_bday"><?php _e('Birthday', 'schema'); ?></label>
      <input type="text" id="schema_bday" name="schema_bday" class="schema_datepicker form_third" value="" />
      <input type="hidden" id="schema_bday-format" class="schema_datepicker-format" value="" />
    </div>

    <div id="sc_street" class="sc_option" style="display:none">
      <label for="schema_street"><?php _e('Address', 'schema'); ?></label>
      <input type="text" name="schema_street" class="form_full" value="" id="schema_street" />
    </div>

    <div id="sc_pobox" class="sc_option" style="display:none">
      <label for="schema_pobox"><?php _e('P.O. Box', 'schema'); ?></label>
      <input type="text" name="schema_pobox" class="form_third schema_numeric" value="" id="schema_pobox" />
    </div>

    <div id="sc_city" class="sc_option" style="display:none">
      <label for="schema_city"><?php _e('City', 'schema'); ?></label>
      <input type="text" name="schema_city" class="form_full" value="" id="schema_city" />
    </div>

    <div id="sc_state" class="sc_option" style="display:none">
      <label for="schema_state"><?php _e('State / Region', 'schema'); ?></label>
      <input type="text" name="schema_state" class="form_third" value="" id="schema_state" />
    </div>

    <div id="sc_postalcode" class="sc_option" style="display:none">
      <label for="schema_postalcode"><?php _e('Postal Code', 'schema'); ?></label>
      <input type="text" name="schema_postalcode" class="form_third" value="" id="schema_postalcode" />
    </div>

    <div id="sc_country" class="sc_option" style="display:none">
      <label for="schema_country"><?php _e('Country', 'schema'); ?></label>
      <select name="schema_country" id="schema_country" class="schema_drop schema_thindrop">
        <option class="holder" value="none">(<?php _e('Select A Country', 'schema'); ?>)</option>
        <option value="US"><?php _e('United States', 'schema'); ?></option>
        <option value="CA"><?php _e('Canada', 'schema'); ?></option>
        <option value="MX"><?php _e('Mexico', 'schema'); ?></option>
        <option value="GB"><?php _e('United Kingdom', 'schema'); ?></option>
        <?php
        $countries = array(
          'AF' => __('Afghanistan', 'schema'),
          'AX' => __('Aland Islands', 'schema'),
          'AL' => __('Albania', 'schema'),
          'DZ' => __('Algeria', 'schema'),
          'AS' => __('American Samoa', 'schema'),
          'AD' => __('Andorra', 'schema'),
          'AO' => __('Angola', 'schema'),
          'AI' => __('Anguilla', 'schema'),
          'AQ' => __('Antarctica', 'schema'),
          'AG' => __('Antigua And Barbuda', 'schema'),
          'AR' => __('Argentina', 'schema'),
          'AM' => __('Armenia', 'schema'),
          'AW' => __('Aruba', 'schema'),
          'AU' => __('Australia', 'schema'),
          'AT' => __('Austria', 'schema'),
          'AZ' => __('Azerbaijan', 'schema'),
          'BS' => __('Bahamas', 'schema'),
          'BH' => __('Bahrain', 'schema'),
          'BD' => __('Bangladesh', 'schema'),
          'BB' => __('Barbados', 'schema'),
          'BY' => __('Belarus', 'schema'),
          'BE' => __('Belgium', 'schema'),
          'BZ' => __('Belize', 'schema'),
          'BJ' => __('Benin', 'schema'),
          'BM' => __('Bermuda', 'schema'),
          'BT' => __('Bhutan', 'schema'),
          'BO' => __('Bolivia, Plurinational State Of', 'schema'),
          'BQ' => __('Bonaire, Sint Eustatius And Saba', 'schema'),
          'BA' => __('Bosnia And Herzegovina', 'schema'),
          'BW' => __('Botswana', 'schema'),
          'BV' => __('Bouvet Island', 'schema'),
          'BR' => __('Brazil', 'schema'),
          'IO' => __('British Indian Ocean Territory', 'schema'),
          'BN' => __('Brunei Darussalam', 'schema'),
          'BG' => __('Bulgaria', 'schema'),
          'BF' => __('Burkina Faso', 'schema'),
          'BI' => __('Burundi', 'schema'),
          'KH' => __('Cambodia', 'schema'),
          'CM' => __('Cameroon', 'schema'),
          'CV' => __('Cape Verde', 'schema'),
          'KY' => __('Cayman Islands', 'schema'),
          'CF' => __('Central African Republic', 'schema'),
          'TD' => __('Chad', 'schema'),
          'CL' => __('Chile', 'schema'),
          'CN' => __('China', 'schema'),
          'CX' => __('Christmas Island', 'schema'),
          'CC' => __('Cocos (Keeling) Islands', 'schema'),
          'CO' => __('Colombia', 'schema'),
          'KM' => __('Comoros', 'schema'),
          'CG' => __('Congo', 'schema'),
          'CD' => __('Congo, The Democratic Republic Of The', 'schema'),
          'CK' => __('Cook Islands', 'schema'),
          'CR' => __('Costa Rica', 'schema'),
          'CI' => __('Cote D\'Ivoire', 'schema'),
          'HR' => __('Croatia', 'schema'),
          'CU' => __('Cuba', 'schema'),
          'CW' => __('Curacao', 'schema'),
          'CY' => __('Cyprus', 'schema'),
          'CZ' => __('Czech Republic', 'schema'),
          'DK' => __('Denmark', 'schema'),
          'DJ' => __('Djibouti', 'schema'),
          'DM' => __('Dominica', 'schema'),
          'DO' => __('Dominican Republic', 'schema'),
          'EC' => __('Ecuador', 'schema'),
          'EG' => __('Egypt', 'schema'),
          'SV' => __('El Salvador', 'schema'),
          'GQ' => __('Equatorial Guinea', 'schema'),
          'ER' => __('Eritrea', 'schema'),
          'EE' => __('Estonia', 'schema'),
          'ET' => __('Ethiopia', 'schema'),
          'FK' => __('Falkland Islands (Malvinas)', 'schema'),
          'FO' => __('Faroe Islands', 'schema'),
          'FJ' => __('Fiji', 'schema'),
          'FI' => __('Finland', 'schema'),
          'FR' => __('France', 'schema'),
          'GF' => __('French Guiana', 'schema'),
          'PF' => __('French Polynesia', 'schema'),
          'TF' => __('French Southern Territories', 'schema'),
          'GA' => __('Gabon', 'schema'),
          'GM' => __('Gambia', 'schema'),
          'GE' => __('Georgia', 'schema'),
          'DE' => __('Germany', 'schema'),
          'GH' => __('Ghana', 'schema'),
          'GI' => __('Gibraltar', 'schema'),
          'GR' => __('Greece', 'schema'),
          'GL' => __('Greenland', 'schema'),
          'GD' => __('Grenada', 'schema'),
          'GP' => __('Guadeloupe', 'schema'),
          'GU' => __('Guam', 'schema'),
          'GT' => __('Guatemala', 'schema'),
          'GG' => __('Guernsey', 'schema'),
          'GN' => __('Guinea', 'schema'),
          'GW' => __('Guinea-Bissau', 'schema'),
          'GY' => __('Guyana', 'schema'),
          'HT' => __('Haiti', 'schema'),
          'HM' => __('Heard Island And Mcdonald Islands', 'schema'),
          'VA' => __('Vatican City', 'schema'),
          'HN' => __('Honduras', 'schema'),
          'HK' => __('Hong Kong', 'schema'),
          'HU' => __('Hungary', 'schema'),
          'IS' => __('Iceland', 'schema'),
          'IN' => __('India', 'schema'),
          'ID' => __('Indonesia', 'schema'),
          'IR' => __('Iran', 'schema'),
          'IQ' => __('Iraq', 'schema'),
          'IE' => __('Ireland', 'schema'),
          'IM' => __('Isle Of Man', 'schema'),
          'IL' => __('Israel', 'schema'),
          'IT' => __('Italy', 'schema'),
          'JM' => __('Jamaica', 'schema'),
          'JP' => __('Japan', 'schema'),
          'JE' => __('Jersey', 'schema'),
          'JO' => __('Jordan', 'schema'),
          'KZ' => __('Kazakhstan', 'schema'),
          'KE' => __('Kenya', 'schema'),
          'KI' => __('Kiribati', 'schema'),
          'KP' => __('North Korea', 'schema'),
          'KR' => __('South Korea', 'schema'),
          'KW' => __('Kuwait', 'schema'),
          'KG' => __('Kyrgyzstan', 'schema'),
          'LA' => __('Laos', 'schema'),
          'LV' => __('Latvia', 'schema'),
          'LB' => __('Lebanon', 'schema'),
          'LS' => __('Lesotho', 'schema'),
          'LR' => __('Liberia', 'schema'),
          'LY' => __('Libya', 'schema'),
          'LI' => __('Liechtenstein', 'schema'),
          'LT' => __('Lithuania', 'schema'),
          'LU' => __('Luxembourg', 'schema'),
          'MO' => __('Macao', 'schema'),
          'MK' => __('Macedonia', 'schema'),
          'MG' => __('Madagascar', 'schema'),
          'MW' => __('Malawi', 'schema'),
          'MY' => __('Malaysia', 'schema'),
          'MV' => __('Maldives', 'schema'),
          'ML' => __('Mali', 'schema'),
          'MT' => __('Malta', 'schema'),
          'MH' => __('Marshall Islands', 'schema'),
          'MQ' => __('Martinique', 'schema'),
          'MR' => __('Mauritania', 'schema'),
          'MU' => __('Mauritius', 'schema'),
          'YT' => __('Mayotte', 'schema'),
          'FM' => __('Micronesia', 'schema'),
          'MD' => __('Moldova', 'schema'),
          'MC' => __('Monaco', 'schema'),
          'MN' => __('Mongolia', 'schema'),
          'ME' => __('Montenegro', 'schema'),
          'MS' => __('Montserrat', 'schema'),
          'MA' => __('Morocco', 'schema'),
          'MZ' => __('Mozambique', 'schema'),
          'MM' => __('Myanmar', 'schema'),
          'NA' => __('Namibia', 'schema'),
          'NR' => __('Nauru', 'schema'),
          'NP' => __('Nepal', 'schema'),
          'NL' => __('Netherlands', 'schema'),
          'NC' => __('New Caledonia', 'schema'),
          'NZ' => __('New Zealand', 'schema'),
          'NI' => __('Nicaragua', 'schema'),
          'NE' => __('Niger', 'schema'),
          'NG' => __('Nigeria', 'schema'),
          'NU' => __('Niue', 'schema'),
          'NF' => __('Norfolk Island', 'schema'),
          'MP' => __('Northern Mariana Islands', 'schema'),
          'NO' => __('Norway', 'schema'),
          'OM' => __('Oman', 'schema'),
          'PK' => __('Pakistan', 'schema'),
          'PW' => __('Palau', 'schema'),
          'PS' => __('Palestine', 'schema'),
          'PA' => __('Panama', 'schema'),
          'PG' => __('Papua New Guinea', 'schema'),
          'PY' => __('Paraguay', 'schema'),
          'PE' => __('Peru', 'schema'),
          'PH' => __('Philippines', 'schema'),
          'PN' => __('Pitcairn', 'schema'),
          'PL' => __('Poland', 'schema'),
          'PT' => __('Portugal', 'schema'),
          'PR' => __('Puerto Rico', 'schema'),
          'QA' => __('Qatar', 'schema'),
          'RE' => __('Reunion', 'schema'),
          'RO' => __('Romania', 'schema'),
          'RU' => __('Russian Federation', 'schema'),
          'RW' => __('Rwanda', 'schema'),
          'BL' => __('St. Barthelemy', 'schema'),
          'SH' => __('St. Helena', 'schema'),
          'KN' => __('St. Kitts And Nevis', 'schema'),
          'LC' => __('St. Lucia', 'schema'),
          'MF' => __('St. Martin (French Part)', 'schema'),
          'PM' => __('St. Pierre And Miquelon', 'schema'),
          'VC' => __('St. Vincent And The Grenadines', 'schema'),
          'WS' => __('Samoa', 'schema'),
          'SM' => __('San Marino', 'schema'),
          'ST' => __('Sao Tome And Principe', 'schema'),
          'SA' => __('Saudi Arabia', 'schema'),
          'SN' => __('Senegal', 'schema'),
          'RS' => __('Serbia', 'schema'),
          'SC' => __('Seychelles', 'schema'),
          'SL' => __('Sierra Leone', 'schema'),
          'SG' => __('Singapore', 'schema'),
          'SX' => __('Sint Maarten (Dutch Part)', 'schema'),
          'SK' => __('Slovakia', 'schema'),
          'SI' => __('Slovenia', 'schema'),
          'SB' => __('Solomon Islands', 'schema'),
          'SO' => __('Somalia', 'schema'),
          'ZA' => __('South Africa', 'schema'),
          'GS' => __('South Georgia', 'schema'),
          'SS' => __('South Sudan', 'schema'),
          'ES' => __('Spain', 'schema'),
          'LK' => __('Sri Lanka', 'schema'),
          'SD' => __('Sudan', 'schema'),
          'SR' => __('Suriname', 'schema'),
          'SJ' => __('Svalbard', 'schema'),
          'SZ' => __('Swaziland', 'schema'),
          'SE' => __('Sweden', 'schema'),
          'CH' => __('Switzerland', 'schema'),
          'SY' => __('Syria', 'schema'),
          'TW' => __('Taiwan', 'schema'),
          'TJ' => __('Tajikistan', 'schema'),
          'TZ' => __('Tanzania', 'schema'),
          'TH' => __('Thailand', 'schema'),
          'TL' => __('Timor-Leste', 'schema'),
          'TG' => __('Togo', 'schema'),
          'TK' => __('Tokelau', 'schema'),
          'TO' => __('Tonga', 'schema'),
          'TT' => __('Trinidad And Tobago', 'schema'),
          'TN' => __('Tunisia', 'schema'),
          'TR' => __('Turkey', 'schema'),
          'TM' => __('Turkmenistan', 'schema'),
          'TC' => __('Turks And Caicos Islands', 'schema'),
          'TV' => __('Tuvalu', 'schema'),
          'UG' => __('Uganda', 'schema'),
          'UA' => __('Ukraine', 'schema'),
          'AE' => __('United Arab Emirates', 'schema'),
          'UM' => __('United States Minor Outlying Islands', 'schema'),
          'UY' => __('Uruguay', 'schema'),
          'UZ' => __('Uzbekistan', 'schema'),
          'VU' => __('Vanuatu', 'schema'),
          'VE' => __('Venezuela', 'schema'),
          'VN' => __('Vietnam', 'schema'),
          'VG' => __('British Virgin Islands ', 'schema'),
          'VI' => __('U.S. Virgin Islands ', 'schema'),
          'WF' => __('Wallis And Futuna', 'schema'),
          'EH' => __('Western Sahara', 'schema'),
          'YE' => __('Yemen', 'schema'),
          'ZM' => __('Zambia', 'schema'),
          'ZW' => __('Zimbabwe', 'schema')
        );
        // sort alphabetical with translated names
        asort($countries);
        // set array of each item
        foreach ($countries as $country_key => $country_name) {
          echo "\n\t<option value='{$country_key}'>{$country_name}</option>";
        }
        ?>
      </select>
    </div>

    <div id="sc_email" class="sc_option" style="display:none">
      <label for="schema_email"><?php _e('Email Address', 'schema'); ?></label>
      <input type="text" name="schema_email" class="form_full" value="" id="schema_email" />
    </div>

    <div id="sc_phone" class="sc_option" style="display:none">
      <label for="schema_phone"><?php _e('Telephone', 'schema'); ?></label>
      <input type="text" name="schema_phone" class="form_half" value="" id="schema_phone" />
    </div>

    <div id="sc_fax" class="sc_option" style="display:none">
      <label for="schema_fax"><?php _e('Fax', 'schema'); ?></label>
      <input type="text" name="schema_fax" class="form_half" value="" id="schema_fax" />
    </div>

    <!-- https://schema.org/JobPosting -->
    <div id="sc_hiring_organization" class="sc_option" style="display:none">
      <label for="schema_hiring_organization"><?php _e('Hiring Organization', 'schema'); ?></label>
      <input type="text" name="schema_hiring_organization" class="form_full" value="" id="schema_hiring_organization" />
    </div>

    <div id="sc_industry" class="sc_option" style="display:none">
      <label for="schema_industry"><?php _e('Industry', 'schema'); ?></label>
      <input type="text" name="schema_industry" class="form_full" value="" id="schema_industry" />
    </div>

    <div id="sc_skills" class="sc_option" style="display:none">
      <label for="schema_skills"><?php _e('Skills', 'schema'); ?></label>
      <input type="text" name="schema_skills" class="form_full" value="" id="schema_skills" />
    </div>

    <!-- https://schema.org/Product -->
    <div id="sc_brand" class="sc_option" style="display:none">
      <label for="schema_brand"><?php _e('Brand', 'schema'); ?></label>
      <input type="text" name="schema_brand" class="form_full" value="" id="schema_brand" />
    </div>

    <div id="sc_manfu" class="sc_option" style="display:none">
      <label for="schema_manfu"><?php _e('Manufacturer', 'schema'); ?></label>
      <input type="text" name="schema_manfu" class="form_full" value="" id="schema_manfu" />
    </div>

    <div id="sc_model" class="sc_option" style="display:none">
      <label for="schema_model"><?php _e('Model', 'schema'); ?></label>
      <input type="text" name="schema_model" class="form_full" value="" id="schema_model" />
    </div>

    <div id="sc_prod_id" class="sc_option" style="display:none">
      <label for="schema_prod_id"><?php _e('Product ID', 'schema'); ?></label>
      <input type="text" name="schema_prod_id" class="form_full" value="" id="schema_prod_id" />
    </div>

    <div id="sc_ratings" class="sc_option" style="display:none">
      <label for="sc_ratings"><?php _e('Aggregate Rating', 'schema'); ?></label>
      <div class="labels_inline">
      <label for="schema_single_rating"><?php _e('Avg Rating', 'schema'); ?></label>
      <input type="text" name="schema_single_rating" class="form_eighth schema_numeric" value="" id="schema_single_rating" />
      <label for="schema_agg_rating"><?php _e('based on', 'schema'); ?> </label>
      <input type="text" name="schema_agg_rating" class="form_eighth schema_numeric" value="" id="schema_agg_rating" />
      <label><?php _e('reviews', 'schema'); ?></label>
      </div>
    </div>

    <div id="sc_reviews" class="sc_option" style="display:none">
      <label for="sc_reviews"><?php _e('Rating', 'schema'); ?></label>
      <div class="labels_inline">
      <label for="schema_user_review"><?php _e('Rating', 'schema'); ?></label>
      <input type="text" name="schema_user_review" class="form_eighth schema_numeric" value="" id="schema_user_review" />
      <label for="schema_min_review"><?php _e('Minimum', 'schema'); ?></label>
      <input type="text" name="schema_min_review" class="form_eighth schema_numeric" value="" id="schema_min_review" />
      <label for="schema_max_review"><?php _e('Minimum', 'schema'); ?></label>
      <input type="text" name="schema_max_review" class="form_eighth schema_numeric" value="" id="schema_max_review" />
      </div>
    </div>


    <div id="sc_price" class="sc_option" style="display:none">
      <label for="schema_price"><?php _e('Price', 'schema'); ?></label>
      <input type="text" name="schema_price" class="form_third sc_currency" value="" id="schema_price" />
    </div>

    <div id="sc_condition" class="sc_option" style="display:none">
      <label for="schema_condition"><?php _ex('Condition', 'product', 'schema'); ?></label>
      <select name="schema_condition" id="schema_condition" class="schema_drop">
        <option class="holder" value="none">(<?php _e('Select', 'schema'); ?>)</option>
        <option value="New"><?php _e('New', 'schema'); ?></option>
        <option value="Used"><?php _e('Used', 'schema'); ?></option>
        <option value="Refurbished"><?php _e('Refurbished', 'schema'); ?></option>
        <option value="Damaged"><?php _e('Damaged', 'schema'); ?></option>
      </select>
    </div>

    <!-- http://schema.org/Book -->
    <div id="sc_author" class="sc_option" style="display:none">
      <label for="schema_author"><?php _e('Author', 'schema'); ?></label>
      <input type="text" name="schema_author" class="form_full" value="" id="schema_author" />
    </div>

    <div id="sc_publisher" class="sc_option" style="display:none">
      <label for="schema_publisher"><?php _e('Publisher', 'schema'); ?></label>
      <input type="text" name="schema_publisher" class="form_full" value="" id="schema_publisher" />
    </div>

    <div id="sc_pubdate" class="sc_option" style="display:none">
      <label for="schema_pubdate"><?php _e('Published Date', 'schema'); ?></label>
      <input type="text" id="schema_pubdate" name="schema_pubdate" class="schema_datepicker form_third" value="" />
      <input type="hidden" id="schema_pubdate-format" class="schema_datepicker-format" value="" />
    </div>

    <div id="sc_edition" class="sc_option" style="display:none">
      <label for="schema_edition"><?php _e('Edition', 'schema'); ?></label>
      <input type="text" name="schema_edition" class="form_full" value="" id="schema_edition" />
    </div>

    <div id="sc_isbn" class="sc_option" style="display:none">
      <label for="schema_isbn"><?php _e('ISBN', 'schema'); ?></label>
      <input type="text" name="schema_isbn" class="form_full" value="" id="schema_isbn" />
    </div>

    <div id="sc_formats" class="sc_option" style="display:none">
      <label class="list_label"><?php _e('Formats', 'schema'); ?></label>
      <div class="form_list">
        <span>
          <input type="checkbox" class="schema_check" id="schema_ebook" name="schema_ebook" value="ebook" />
          <label for="schema_ebook" rel="checker"><?php _e('Ebook', 'schema'); ?></label>
        </span>
        <span>
          <input type="checkbox" class="schema_check" id="schema_paperback" name="schema_paperback" value="paperback" />
          <label for="schema_paperback" rel="checker"><?php _e('Paperback', 'schema'); ?></label>
        </span>
        <span>
          <input type="checkbox" class="schema_check" id="schema_hardcover" name="schema_hardcover" value="hardcover" />
          <label for="schema_hardcover" rel="checker"><?php _e('Hardcover', 'schema'); ?></label>
         </span>
      </div>
    </div>

    <div id="sc_revdate" class="sc_option" style="display:none">
      <label for="schema_revdate"><?php _e('Review Date', 'schema'); ?></label>
      <input type="text" id="schema_revdate" name="schema_revdate" class="schema_datepicker form_third" value="" />
      <input type="hidden" id="schema_revdate-format" class="schema_datepicker-format" value="" />
    </div>

    <div id="sc_preptime" class="sc_option" style="display:none">
      <label for="sc_preptime"><?php _e('Prep Time', 'schema'); ?></label>
      <div class="labels_inline">
        <label for="schema_prep_hours"><?php _e('Hours', 'schema'); ?></label>
        <input type="text" name="schema_prep_hours" class="form_eighth schema_numeric" value="" id="schema_prep_hours" />
        <label for="schema_prep_mins"><?php _e('Minutes', 'schema'); ?></label>
        <input type="text" name="schema_prep_mins" class="form_eighth schema_numeric" value="" id="schema_prep_mins" />
      </div>
    </div>

    <div id="sc_cooktime" class="sc_option" style="display:none">
      <label for="sc_cooktime"><?php _e('Cook Time', 'schema'); ?></label>
      <div class="labels_inline">
        <label for="schema_cook_hours"><?php _e('Hours', 'schema'); ?></label>
        <input type="text" name="schema_cook_hours" class="form_eighth schema_numeric" value="" id="schema_cook_hours" />
        <label for="schema_cook_mins"><?php _e('Minutes', 'schema'); ?></label>
        <input type="text" name="schema_cook_mins" class="form_eighth schema_numeric" value="" id="schema_cook_mins" />
      </div>
    </div>

    <div id="sc_yield" class="sc_option" style="display:none">
      <label for="schema_yield"><?php _e('Yield', 'schema'); ?></label>
      <input type="text" name="schema_yield" class="form_third" value="" id="schema_yield" />
      <label class="additional">(<?php _e('serving size', 'schema'); ?>)</label>
    </div>

    <div id="sc_calories" class="sc_option" style="display:none">
      <label for="schema_calories"><?php _e('Calories', 'schema'); ?></label>
      <input type="text" name="schema_calories" class="form_third schema_numeric" value="" id="schema_calories" />
    </div>

    <div id="sc_fatcount" class="sc_option" style="display:none">
      <label for="schema_fatcount"><?php _e('Fat', 'schema'); ?></label>
      <input type="text" name="schema_fatcount" class="form_third schema_numeric" value="" id="schema_fatcount" />
      <label class="additional">(<?php _e('in grams', 'schema'); ?>)</label>
    </div>

    <div id="sc_sugarcount" class="sc_option" style="display:none">
      <label for="schema_sugarcount"><?php _e('Sugar', 'schema'); ?></label>
      <input type="text" name="schema_sugarcount" class="form_third schema_numeric" value="" id="schema_sugarcount" />
      <label class="additional">(<?php _e('in grams', 'schema'); ?>)</label>
    </div>

    <div id="sc_saltcount" class="sc_option" style="display:none">
      <label for="schema_saltcount"><?php _e('Sodium', 'schema'); ?></label>
      <input type="text" name="schema_saltcount" class="form_third schema_numeric" value="" id="schema_saltcount" />
      <label class="additional">(<?php _e('in milligrams', 'schema'); ?>)</label>
    </div>

    <div id="sc_ingrt_1" class="sc_option sc_ingrt sc_repeater ig_repeat" style="display:none">
      <label for="schema_ingrt_1"><?php _e('Ingredient', 'schema'); ?></label>
      <input type="text" name="schema_ingrt_1" class="form_half ingrt_input" value="" id="schema_ingrt_1" />
      <label class="additional">(<?php _e('include both type and amount', 'schema'); ?>)</label>
    </div>

    <input type="button" class="clone_button" id="clone_ingrt" value="<?php _e('Add Another Ingredient', 'schema'); ?>" style="display:none;" />

    <div id="sc_instructions" class="sc_option" style="display:none">
      <label for="schema_instructions"><?php _e('Instructions', 'schema'); ?></label>
      <textarea name="schema_instructions" id="schema_instructions"></textarea>
    </div>

    <!-- button for inserting -->
    <div class="insert_button" style="display:none">
      <input class="schema_insert schema_button" type="button" value="<?php _e('Insert'); ?>" onclick="InsertSchema();"/>
      <input class="schema_cancel schema_clear schema_button" type="button" value="<?php _e('Cancel'); ?>" onclick="tb_remove(); return false;"/>
    </div>

    <!-- various messages -->
    <div id="sc_messages">
      <p class="start"><?php _e('Select a schema type above to get started', 'schema'); ?></p>
      <p class="pending" style="display:none;"><?php _e('This schema type is currently being constructed.', 'schema'); ?></p>
    </div>

  </div>
</div>
