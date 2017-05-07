<h1>
  {$lblMembersRegistration}{option:isGroupLoaded}: {$group.locale.title}{/option:isGroupLoaded}
</h1>
<section id="members-registration" class="module module-members">
  {option:showTypeChoicePage}
  <div class="row">
    <div class="col-md-12">
      {iteration:types}
      <div class="jumbotron">
        <h2>{$types.label|ucfirst}</h2>
        <p>{$types.message}</p>
        <p>
          <a class="btn btn-default btn-lg" href="{$var|geturlforblock:'Members':'Registration'}/{$types.action}" role="button" title="{$types.label|ucfirst}">
            {$lblMembersRegister}
          </a>
        </p>
      </div>
      {/iteration:types}
    </div>
  </div>
  {/option:showTypeChoicePage}
  {option:!showTypeChoicePage}
  {form:registration}
    {option:isEmailRegistration}
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="email">
            {$lblEmail|ucfirst}
            <abbr class="glyphicon glyphicon-asterisk" title="{$lblRequiredField|ucfirst}"></abbr>
          </label>
          {$txtEmail} {$txtEmailError}
        </div>
      </div>
    </div>
    {/option:isEmailRegistration}
    {option:!isEmailRegistration}
    <div class="row">
      <div class="col-md-12">
        <h3>{$lblProfile|ucfirst}</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        {option:showTypeChoice}
        {option:!isTypeLoaded}
        <div class="form-group">
          <ul class="list-inline">
            {iteration:type}
            <li class="radio">
              <label for="{$type.id}">{$type.rbtType} {$type.label}</label>
            </li>
            {/iteration:type}
          </ul>
        </div>
        {/option:!isTypeLoaded}
        {/option:showTypeChoice}
        <div class="form-group{option:txtEmailError} has-error{/option:txtEmailError}">
          <label for="email">
            {$lblEmail|ucfirst}
            <abbr class="glyphicon glyphicon-asterisk" title="{$lblRequiredField|ucfirst}"></abbr>
          </label>
          {$txtEmail}
          {option:txtEmailError}
          <span class="help-block">{$txtEmailError}</span>
          {/option:txtEmailError}
        </div>
        <div class="form-group{option:txtDisplayNameError} has-error{/option:txtDisplayNameError}">
          <label for="displayName">
            {$lblDisplayName|ucfirst}
          </label>
          {$txtDisplayName}
          {option:txtDisplayNameError}
          <span class="help-block">{$txtDisplayNameError}</span>
          {/option:txtDisplayNameError}
        </div>
        <div class="form-group{option:txtPasswordError} has-error{/option:txtPasswordError}">
          <label for="password">
            {$lblPassword|ucfirst}
            <abbr class="glyphicon glyphicon-asterisk" title="{$lblRequiredField|ucfirst}"></abbr>
          </label>
          {$txtPassword}
          {option:txtPasswordError}
          <span class="help-block">{$txtPasswordError}</span>
          {/option:txtPasswordError}
        </div>
        <div class="form-group{option:txtPasswordConfirmError} has-error{/option:txtPasswordConfirmError}">
          <label for="passwordConfirm">
            {$lblPasswordConfirm|ucfirst}
            <abbr class="glyphicon glyphicon-asterisk" title="{$lblRequiredField|ucfirst}"></abbr>
          </label>
          {$txtPasswordConfirm}
          {option:txtPasswordConfirmError}
          <span class="help-block">{$txtPasswordConfirmError}</span>
          {/option:txtPasswordConfirmError}
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <h3>{$lblMember|ucfirst}</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        {option:isTypeNatural}
        <div class="form-group{option:txtFirstNameError} has-error{/option:txtFirstNameError}">
          <label for="firstName">{$lblFirstName|ucfirst}</label>
          {$txtFirstName}
          {option:txtFirstNameError}
          <span class="help-block">{$txtFirstNameError}</span>
          {/option:txtFirstNameError}
        </div>
        <div class="form-group{option:txtLastNameError} has-error{/option:txtLastNameError}">
          <label for="lastName">{$lblLastName|ucfirst}</label>
          {$txtLastName}
          {option:txtLastNameError}
          <span class="help-block">{$txtLastNameError}</span>
          {/option:txtLastNameError}
        </div>
        {/option:isTypeNatural}
        {option:isTypeJuridical}
        <div class="form-group{option:txtCompanyError} has-error{/option:txtCompanyError}">
          <label for="company">{$lblCompany|ucfirst}</label>
          {$txtCompany}
          {option:txtCompanyError}
          <span class="help-block">{$txtCompanyError}</span>
          {/option:txtCompanyError}
        </div>
        <div class="form-group{option:txtCompanyCodeError} has-error{/option:txtCompanyCodeError}">
          <label for="companyCode">{$lblCompanyCode|ucfirst}</label>
          {$txtCompanyCode}
          {option:txtCompanyCodeError}
          <span class="help-block">{$txtCompanyCodeError}</span>
          {/option:txtCompanyCodeError}
        </div>
        <div class="form-group{option:txtVatIdentifierError} has-error{/option:txtVatIdentifierError}">
          <label for="vatIdentifier">{$lblVatIdentifier|ucfirst}</label>
          {$txtVatIdentifier}
          {option:txtVatIdentifierError}
          <span class="help-block">{$txtVatIdentifierError}</span>
          {/option:txtVatIdentifierError}
        </div>
        {/option:isTypeJuridical}
        <div class="form-group{option:ddmGenderError} has-error{/option:ddmGenderError}">
          <label for="gender">{$lblGender|ucfirst}</label>
          {$ddmGender}
          {option:ddmGenderError}
          <span class="help-block">{$ddmGenderError}</span>
          {/option:ddmGenderError}
        </div>
        <div class="form-group">
          <label>{$lblDateBirth|ucfirst}</label>
          <div class="row">
            <div class="col-xs-4">
              {$ddmDateBirthYear} {$ddmDateBirthYearError}
            </div>
            <div class="col-xs-4">
              {$ddmDateBirthMonth} {$ddmDateBirthMonthError}
            </div>
            <div class="col-xs-4">
              {$ddmDateBirthDay} {$ddmDateBirthDayError}
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="source">{$lblSource|ucfirst}</label>
          {$ddmSource}
        </div>
      </div>
    </div>
    {/option:!isEmailRegistration}
    <div class="row">
      <div class="col-md-12">
        <div class="form-group{option:chkTermsError} has-error{/option:chkTermsError}">
          <ul class="list-inline">
            <li class="checkbox">
              <label for="terms">{$chkTerms} {$msgMembersTerms|sprintf:{$urlTerms}}</label>
              {option:chkTermsError}
              <span class="help-block">{$chkTermsError}</span>
              {/option:chkTermsError}
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="btn-toolbar">
          <div class="btn-group pull-right" role="group">
            <button id="addButton" type="submit" name="add" class="btn btn-default">
              <span class="glyphicon glyphicon-plus"></span>&nbsp;
              {$lblRegister|ucfirst}
            </button>
          </div>
        </div>
      </div>
    </div>
  {/form:registration}
  {/option:!showTypeChoicePage}
</section>
