{extends file='parent:frontend/standorte/index.tpl'}
{block name="stores_list_sort_options"}
    {*if $shopId != 1*}
        <option value="distance_ASC">Sortierung: nach Entfernung</option>
    {*/if*}
    {$smarty.block.parent}
{/block}

{block name="frontend_index_content"}
    {$smarty.block.parent}
    {if $shopId != 1}
        <input type="hidden" id="ajaxUrlDistance" value="{url module=frontend controller=ExtendStandorte action=list}" />
    {/if}
{/block}
