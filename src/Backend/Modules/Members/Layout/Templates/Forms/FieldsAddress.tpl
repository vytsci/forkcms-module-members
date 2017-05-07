<div class="form-group">
  <ul class="list-unstyled">
    <li class="checkbox">
      <label for="primary">
        {$chkPrimary} {$lblPrimary|ucfirst}
      </label>
    </li>
  </ul>
</div>
<div class="form-group">
  <label for="country">{$lblCountry|ucfirst}</label>
  {$ddmCountry} {$ddmCountryError}
</div>
<div class="form-group">
  <label for="city">{$lblCity|ucfirst}</label>
  {$txtCity} {$txtCityError}
</div>
<div class="form-group">
  <label for="address">{$lblAddress|ucfirst}</label>
  {$txtAddress} {$txtAddressError}
</div>
<div class="form-group">
  <label for="phone">{$lblPhone|ucfirst}</label>
  {$txtPhone} {$txtPhoneError}
</div>
