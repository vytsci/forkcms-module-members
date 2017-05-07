<div class="form-group">
  <ul class="list-unstyled">
    <li class="checkbox">
      <label for="addressPrimary">
        {$chkAddressPrimary} {$lblMembersAddressPrimary|ucfirst}
      </label>
    </li>
    {* <li class="checkbox">
      <label for="addressBilling">
        {$chkAddressBilling} {$lblMembersAddressBilling|ucfirst}
      </label>
    </li> *}
  </ul>
</div>
<div class="form-group">
  <label for="addressCountry">{$lblMembersAddressCountry|ucfirst}</label>
  {$ddmAddressCountry} {$ddmAddressCountryError}
</div>
<div class="form-group">
  <label for="addressState">{$lblMembersAddressState|ucfirst}</label>
  {$ddmAddressState} {$ddmAddressStateError}
</div>
<div class="form-group">
  <label for="addressCity">{$lblMembersAddressCity|ucfirst}</label>
  {$ddmAddressCity} {$ddmAddressCityError}
</div>
<div class="form-group">
  <label for="addressAddress">{$lblMembersAddressAddress|ucfirst}</label>
  {$txtAddressAddress} {$txtAddressAddressError}
</div>
<div class="form-group">
  <label for="addressPhone">{$lblMembersAddressPhone|ucfirst}</label>
  {$txtAddressPhone} {$txtAddressPhoneError}
</div>
