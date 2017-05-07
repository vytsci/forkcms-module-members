<section class="widget widget-members widget-members-add-address">
  {form:address}
    <div class="row">
      <div class="col-md-12">
        {include:{$FRONTEND_MODULES_PATH}/Members/Layout/Templates/Forms/FieldsAddress.tpl}
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="btn-toolbar">
          <div class="btn-group pull-right" role="group">
            <button id="addButton" type="submit" name="add" class="btn btn-primary">
              <span class="glyphicon glyphicon-plus"></span>&nbsp;
              {$lblMembersAddressAdd|ucfirst}
            </button>
          </div>
        </div>
      </div>
    </div>
  {/form:address}
</section>
