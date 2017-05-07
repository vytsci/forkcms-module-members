{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}
<div class="row fork-module-heading">
  <div class="col-md-12">
    <h2>{$lblGroups|ucfirst}</h2>
    <div class="btn-toolbar pull-right">
      <div class="btn-group" role="group">
        {option:showMembersAddGroup}
        <a href="{$var|geturl:'add_group'}" class="btn btn-default" title="{$lblAddGroup|ucfirst}">
          <span class="glyphicon glyphicon-plus"></span>&nbsp;
          {$lblAddGroup|ucfirst}
        </a>
        {/option:showMembersAddGroup}
      </div>
    </div>
  </div>
</div>
<div class="row fork-module-content">
  <div class="col-md-12">
    {option:dataGrid}
    <div id="dataGrid-{$dataGrid.id}" class="panel panel-default">
      {$dataGrid}
    </div>
    {/option:dataGrid}
    {option:!dataGrid}
    <div class="panel-body">
      <p>{$msgNoItems}</p>
    </div>
    {/option:!dataGrid}
  </div>
</div>
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
