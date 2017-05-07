<h1>{$lblMembersRequisites|ucfirst}</h1>
<section id="members-requisites" class="module module-members">
  {option:formErrors}
  <p class="alert alert-danger">{$formErrors}</p>
  {/option:formErrors}
  <p>
    {$lblMembersRequisitesStatus}:
    {option:!requisites.id}
    <span class="text-info">{$msgMembersRequisitesStatusEmpty}</span>
    {/option:!requisites.id}
    {option:requisites.id}
    {option:requisites.status.is_pending}
    <span class="text-warning">{$msgMembersRequisitesStatusPending}</span>
    {/option:requisites.status.is_pending}
    {option:requisites.status.is_approved}
    <span class="text-success">{$msgMembersRequisitesStatusApproved}</span>
    {/option:requisites.status.is_approved}
    {option:requisites.status.is_rejected}
    <span class="text-danger">{$msgMembersRequisitesStatusRejected}</span>
    {/option:requisites.status.is_rejected}
    {/option:requisites.id}
  </p>
  {form:requisites}
    <h2>{$lblMembersRequisitesGeneral|ucfirst}</h2>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="email">
            {$lblType|ucfirst}
          </label>
          {$ddmType}
        </div>
        <div class="form-group">
          <label for="businessEntityType">
            {$lblRequisitesBusinessEntityType|ucfirst}
          </label>
          {option:txtBusinessEntityTypeError}
          <p class="text-error">{$txtBusinessEntityTypeError}</p>
          {/option:txtBusinessEntityTypeError}
          {$txtBusinessEntityType}
        </div>
        <div class="form-group">
          <label for="company">
            {$lblRequisitesCompany|ucfirst}
          </label>
          {option:txtCompanyError}
          <p class="text-error">{$txtCompanyError}</p>
          {/option:txtCompanyError}
          {$txtCompany}
        </div>
        <div class="form-group">
          <label for="companyCode">
            {$lblMembersRequisitesCompanyCode|ucfirst}
          </label>
          {option:txtCompanyCodeError}
          <p class="text-error">{$txtCompanyCodeError}</p>
          {/option:txtCompanyCodeError}
          {$txtCompanyCode}
        </div>
        <div class="form-group">
          <label for="vatIdentifier">
            {$lblMembersRequisitesVatIdentifier|ucfirst}
          </label>
          {option:txtVatIdentifierError}
          <p class="text-error">{$txtVatIdentifierError}</p>
          {/option:txtVatIdentifierError}
          {$txtVatIdentifier}
        </div>
      </div>
    </div>
    <h2>{$lblMembersRequisitesAddress|ucfirst}</h2>
    <div class="row">
      <div class="col-md-12">
        {include:{$FRONTEND_MODULES_PATH}/Members/Layout/Templates/Forms/FieldsRequisitesAddress.tpl}
      </div>
    </div>
    <h2>{$lblMembersRequisitesBank|ucfirst}</h2>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="bank">
            {$lblMembersRequisitesBank|ucfirst}
          </label>
          {option:txtBankError}
          <p class="text-error">{$txtBankError}</p>
          {/option:txtBankError}
          {$txtBank}
        </div>
        <div class="form-group">
          <label for="bankAccount">
            {$lblMembersRequisitesBankAccount|ucfirst}
          </label>
          {option:txtBankAccountError}
          <p class="text-error">{$txtBankAccountError}</p>
          {/option:txtBankAccountError}
          {$txtBankAccount}
        </div>
        <div class="form-group">
          <label for="bankSwift">
            {$lblMembersRequisitesBankSwift|ucfirst}
          </label>
          {option:txtBankSwiftError}
          <p class="text-error">{$txtBankSwiftError}</p>
          {/option:txtBankSwiftError}
          {$txtBankSwift}
        </div>
      </div>
    </div>
    {option:!hasPending}
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <ul class="list-unstyled">
            <li class="checkbox">
              {option:chkTermsError}
              <p class="text-danger">{$chkTermsError}</p>
              {/option:chkTermsError}
              <label for="terms">{$chkTerms} {$msgMembersRequisitesTerms}</label>
            </li>
          </ul>
        </div>
        <div class="btn-toolbar">
          <div class="btn-group pull-right" role="group">
            {option:hasPending}
            <button id="edit-button" type="button" class="btn btn-default">
              <span class="glyphicon glyphicon-pencil"></span>&nbsp;
              {$lblEdit|ucfirst}
            </button>
            {/option:hasPending}
            <button id="save-button" type="submit" name="save" class="btn btn-default">
              <span class="glyphicon glyphicon-plus"></span>&nbsp;
              {$lblMembersRequisitesSave|ucfirst}
            </button>
          </div>
        </div>
      </div>
    </div>
    {/option:!hasPending}
  {/form:requisites}
</section>
