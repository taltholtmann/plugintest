{extends file='parent:frontend/index/index.tpl'}

{block name='frontend_index_content_left'}

{/block}

{block name="frontend_index_content"}
    {* Google Map *}
    {action module=widgets controller=Map action=map}
    
    {* Unterseiten-Titel *}
    <div class="store--title">
        <h1>Unsere Standorte</h1>
        <hr class="title--line">
    </div>
    
    {if $activeStore}
        <p class="your-selected-store">
            Ihr ausgewählter Standort
        </p>
        <div class="stores--list-item active" id="store--list-item{$activeStore.id}">   
            <div class="stores--list-item-content">
                <div class="stores--list-item-information">
                    <div class="stores--list-item-title">
                        <h3>RHG {$activeStore.type}<br>{$activeStore.location}</h3>
                    </div>
                    <div class="stores--list-item-address">
                        {$activeStore.street}<br>
                        {$activeStore.postcode} {$activeStore.location}
                    </div>
                </div>
                <div class="stores--list-item-assortments">
                    <p>
                        <strong>Sortiment:</strong>
                        {foreach $activeStore.assortments as $assortment}
                            {if $assortment@last == false}
                                <span class="">{$assortment},</span>
                            {else}
                                <span>{$assortment}</span> 
                            {/if}
                        {/foreach}
                    </p>
                </div>
            </div>
            <div class="stores--list-item-buttons">
                <a class="btn is--primary" href="{$activeStore.storeLink|replace:"/":""}">auswählen</a> <br>
                <a class="btn is--secondary" href="standort?id={$activeStore.id}">zur Marktseite</a>
            </div>
        </div>
    {/if}
    
    <div class="stores--list-container" data-ajaxUrl="{url module=frontend controller=Standorte action=list}">
        {* Filter/Sortier-Selectionfelder für die Standorte-Liste *}
        <div class="store--list-filte-panel">
            <select name="stores-assort-select" id="stores-filter-field">
                <option value="showAll" selected="selected">Alle Sortimente anzeigen</option>
                {foreach $assortments as $assort}
                    <option value="{$assort}">{$assort}</option>
                {/foreach}   
            </select>
            <select name="stores-list-sort" id="stores-sort-field">
                {block name="stores_list_sort_options"}
                    <option value="location_ASC">A-Z</option>
                    <option value="location_DESC">Z-A</option>
                    <option value="postcode_ASC">PLZ 0-9</option>
                    <option value="postcode_DESC">PLZ 9-0</option>
                {/block}
            </select>
        </div>
        {include file="frontend/standorte/list.tpl"}
    </div>
{/block}