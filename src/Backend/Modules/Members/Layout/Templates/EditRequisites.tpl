{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}
<div class="row fork-module-heading">
  <div class="col-md-12">
    <h2>
      {$lblEditRequisites|ucfirst}
    </h2>
  </div>
</div>
<div class="row fork-module-content">
  <div class="col-md-12">
    {option:member}
    <h3>{$lblMember|ucfirst}</h3>
    <table class="table">
      <tr>
        <th>{$lblFirstName|ucfirst}</th>
        <td>{$member.first_name}</td>
      </tr>
      <tr>
        <th>{$lblLastName|ucfirst}</th>
        <td>{$member.last_name}</td>
      </tr>
      <tr>
        <th>{$lblDisplayName|ucfirst}</th>
        <td>{$member.display_name}</td>
      </tr>
      <tr>
        <th>{$lblEmail|ucfirst}</th>
        <td>{$member.email}</td>
      </tr>
    </table>
    {/option:member}
    {option:requisites}
    <h3>{$lblCurrentRequisites|ucfirst}</h3>
    <table class="table">
      <tr>
        <th>{$lblStatus|ucfirst}</th>
        <td>
          {option:requisites.status.is_pending}
          <span class="text-warning">{$lblStatusPending|ucfirst}</span>
          {/option:requisites.status.is_pending}
          {option:requisites.status.is_approved}
          <span class="text-success">{$lblStatusApproved|ucfirst}</span>
          {/option:requisites.status.is_approved}
          {option:requisites.status.is_rejected}
          <span class="text-danger">{$lblStatusRejected|ucfirst}</span>
          {/option:requisites.status.is_rejected}
        </td>
      </tr>
      <tr>
        <th>{$lblBusinessEntityType|ucfirst}</th>
        <td>{$requisites.business_entity_type}</td>
      </tr>
      <tr>
        <th>{$lblCompany|ucfirst}</th>
        <td>{$requisites.company}</td>
      </tr>
      <tr>
        <th>{$lblCompanyCode|ucfirst}</th>
        <td>{$requisites.company_code}</td>
      </tr>
      <tr>
        <th>{$lblVatIdentifier|ucfirst}</th>
        <td>{$requisites.vat_identifier}</td>
      </tr>
      <tr>
        <th>{$lblBank|ucfirst}</th>
        <td>{$requisites.bank}</td>
      </tr>
      <tr>
        <th>{$lblBankAccount|ucfirst}</th>
        <td>{$requisites.bank_account}</td>
      </tr>
      <tr>
        <th>{$lblBankSwift|ucfirst}</th>
        <td>{$requisites.bank_swift}</td>
      </tr>
    </table>
    {/option:requisites}
    {option:address}
    <h3>{$lblAddressBilling|ucfirst}</h3>
    <table class="table">
      <tr>
        <th>{$lblCountry|ucfirst}</th>
        <td>{$address.country.locale.name}</td>
      </tr>
      <tr>
        <th>{$lblCity|ucfirst}</th>
        <td>{$address.city.locale.name}</td>
      </tr>
      <tr>
        <th>{$lblAddress|ucfirst}</th>
        <td>{$address.address}</td>
      </tr>
      <tr>
        <th>{$lblPhone|ucfirst}</th>
        <td>{$address.phone}</td>
      </tr>
    </table>
    {/option:address}
    <h3>{$lblHistory|ucfirst}</h3>
    <table class="table">
      <thead>
        <tr>
          <th>
            {$lblCreatedOn|ucfirst}
          </th>
          <th>
            {$lblBusinessEntityType|ucfirst}
          </th>
          <th>
            {$lblCompany|ucfirst}
          </th>
          <th>
            {$lblCompanyCode|ucfirst}
          </th>
          <th>
            {$lblVatIdentifier|ucfirst}
          </th>
          <th>
            {$lblBank|ucfirst}
          </th>
          <th>
            {$lblBankAccount|ucfirst}
          </th>
          <th>
            {$lblBankSwift|ucfirst}
          </th>
          <th>
            {$lblStatus|ucfirst}
          </th>
        </tr>
      </thead>
      <tbody>
        {iteration:history}
        <tr>
          <td>{$history.created_on}</td>
          <td>{$history.business_entity_type}</td>
          <td>{$history.company}</td>
          <td>{$history.company_code}</td>
          <td>{$history.vat_identifier}</td>
          <td>{$history.bank}</td>
          <td>{$history.bank_account}</td>
          <td>{$history.bank_swift}</td>
          <td>{$history.status}</td>
        </tr>
        {/iteration:history}
      </tbody>
    </table>
  </div>
</div>
<div class="row fork-module-actions">
  <div class="col-md-12">
    <div class="btn-toolbar">
      <div class="btn-group pull-right" role="group">
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmReject">
          <span class="glyphicon glyphicon-ok"></span>
          {$lblReject|ucfirst}
        </button>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmApprove">
          <span class="glyphicon glyphicon-ok"></span>
          {$lblApprove|ucfirst}
        </button>
      </div>
    </div>
    <form action="{$var|geturl:'mass_requisites_action'}" method="get" class="forkForms">
      <input type="hidden" name="id[]" value="{$requisites.id}" />
      <div class="modal fade" id="confirmApprove" tabindex="-1" role="dialog" aria-labelledby="{$lblApprove|ucfirst}" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <span class="modal-title h4">{$lblApprove|ucfirst}</span>
            </div>
            <div class="modal-body">
              <p>{$msgConfirmApprove}</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">{$lblCancel|ucfirst}</button>
              <button type="submit" name="action" value="approve" class="btn btn-primary">{$lblOK|ucfirst}</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="confirmReject" tabindex="-1" role="dialog" aria-labelledby="{$lblReject|ucfirst}" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <span class="modal-title h4">{$lblReject|ucfirst}</span>
            </div>
            <div class="modal-body">
              <p>{$msgConfirmReject}</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">{$lblCancel|ucfirst}</button>
              <button type="submit" name="action" value="reject" class="btn btn-primary">{$lblOK|ucfirst}</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
