<div class="tabs w-full p-1 pt-2 pb-0 bg-white ">
  <div class="lg:flex lg:flex-wrap border-0 mx-2">
    <a href="#{{ content.title.singular|lower }}" class="mx-1 p-2 px-6
    rounded-t-lg bg-gray-100 hover:bg-slate-200
    hover:font-bold">{{ content.title.singular }}</a>
    {% if content.tabs is defined %}
      {% for tkey, tabs in content.tabs %}
      <a href="#tab1" class="mx-1 p-2 px-6 rounded-t-lg
      bg-gray-100 hover:bg-gray-200">{{ tabs }}</a>
      {% endfor %}
    {% endif %}
    {% if content.table.srt|default(false) == true %}
    <a href="#{{ locale.backend.tabs.sorting|lower }}" class="mx-1 p-2 px-6
    rounded-t-lg bg-gray-200 hover:bg-slate-200
    hover:font-bold">{{ locale.backend.tabs.sorting }}</a>
    {% endif %}
    <a href="#{{ locale.backend.tabs.auditing|lower }}" class="mx-1 p-2 px-6
    rounded-t-lg bg-gray-200 hover:bg-slate-200
    hover:font-bold">{{ locale.backend.tabs.auditing }}</a>
  </div>
</div>
