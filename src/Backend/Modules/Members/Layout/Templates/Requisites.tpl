{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}
<div class="row fork-module-heading">
  <div class="col-md-12">
    <h2>{$lblRequisites|ucfirst}</h2>
  </div>
</div>
{*<div class="row fork-module-content">
  <div class="col-md-12">
    {form:filter}
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="search">{$lblSearch|ucfirst}</label>
                {$txtSearch} {$txtSearchError}
              </div>
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <div class="btn-toolbar">
            <div class="btn-group pull-right">
              <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-refresh"></span>&nbsp;
                {$lblUpdateFilter|ucfirst}
              </button>
            </div>
          </div>
        </div>
      </div>
    {/form:filter}
  </div>
</div>*}
<div class="row fork-module-content">
  <div class="col-md-12">
    {option:dgRequisites}
    <form action="{$var|geturl:'mass_requisites_action'}" method="get" class="forkForms">
      {$dgRequisites}
      <div class="modal fade" id="confirmApprove" tabindex="-1" role="dialog" aria-labelledby="{$lblApprove|ucfirst}" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <span class="modal-title h4">{$lblApprove|ucfirst}</span>
            </div>
            <div class="modal-body">
              <p>{$msgConfirmMassApprove}</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">{$lblCancel|ucfirst}</button>
              <button type="submit" class="btn btn-primary">{$lblOK|ucfirst}</button>
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
              <p>{$msgConfirmMassReject}</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">{$lblCancel|ucfirst}</button>
              <button type="submit" class="btn btn-primary">{$lblOK|ucfirst}</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    {/option:dgRequisites}
    {option:!dgRequisites}
    <p>{$msgNoItems}</p>
    {/option:!dgRequisites}
  </div>
</div>
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
