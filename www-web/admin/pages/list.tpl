{% if content.columns is not empty %}
  {% include '/partials/breadcrumbs.tpl' %}
  {% include '/partials/pagination.tpl' %}
  <div class="
    list-header-wrapper bg-white sticky top-0
    lg:flex
  ">
    {% if 'U' not in content.table.crd and 'D' not in content.table.crd %}
      <div class="
        flex-1 list-header font-bold
        text-center p-3 grow-0 basis-[1%]
      "></div>
    {% else %}
      <div class="
        flex-1 list-header font-bold
        text-center p-3 grow-0 basis-[5%]
      "></div>
    {% endif %}
    {% for ckey, citem in content.columns %}
      {% if citem.type != 'key' and citem.type != 'uuid' %}
        {% if 'R' in citem.crud %}
        <div class="
          flex-1 list-header font-bold
          text-center p-3
        ">
          {{ citem.name|title }}
        </div>
        {% endif %}
      {% endif %}
    {% endfor %}
  </div>
  {% if content.data is not empty %}
    {% set xrows = [] %}
    {% if content.data[0] is not defined %}
      {% set xrows = xrows|merge([content.data]) %}
    {% else %}
      {% set xrows = content.data %}
    {% endif %}
    {% for dkey, ditem in xrows %}
    <div class="records flex even:bg-slate-50 odd:bg-slate-100">
      <input type="hidden"
        key="{{ content.table.hsh }}"
        name="{{ content.table.hsh }}"
        value="{{ ditem[content.table.pfx~content.table.key] }}"
      />
      {% if 'U' not in content.table.crd and 'D' not in content.table.crd %}
        <div class="flex-1 p-1 grow-0 basis-[1%] text-center">
        </div>
      {% else %}
        <div class="flex-1 p-1 grow-0 basis-[5%] text-center">
          {% if 'U' in content.table.crd %}
          <a href="
            {{ host~'/'~admin~'/'~links|join('/')~'/'~edit~'/'~
            ditem[content.table.pfx~content.table.key] }}
          ">
            <i class="
              fa-solid fa-file-pen m-[0.3em] text-green-400
            "></i>
          </a>
          {% endif %}
          {% if 'D' in content.table.crd %}
          <a href="
            {{ host~'/'~admin~'/'~links|join('/')~'/'~delete~'/'~
            ditem[content.table.pfx~content.table.key] }}
          ">
            <i class="
            fa-solid fa-trash-can m-[0.3em] text-red-400
            "></i>
          </a>
          {% endif %}
        </div>
      {% endif %}
      {% for ckey, citem in content.columns %}
        {% if ditem[citem.flds] is defined and 'R' in citem.crud %}
          <div class="column flex-1 p-1
          {{ citem.align == 'center' ? 'text-center' : '' }}">
            {% include [
              '/forms/'~citem.type~'.tpl',
              '/forms/text.tpl'
            ] %}
          </div>
        {% endif %}
      {% endfor %}
    </div>
    {% endfor %}
  {% else %}
    <h2 class="
      list-error w-full m-4 text-center text-[3em] font-bold
      text-red-400 lg:text-[5em]
    ">
      <i class="fa-solid fa-circle-exclamation m-0 my-3 mt-[0.3em] fa-2x"></i>
      <br />
      {% if content.title.plural is defined %}
        No {{ content.title.plural }} Found
        {{ locale.backend.content.errors.no_records_configured|replace({
          '[%APP%]' : content.title.plural
        }) }}
      {% else %}
        {{ locale.backend.content.errors.no_records_configured }}
      {% endif %}
    <h2>
  {% endif %}
  {% set hidebc = true %}
  {% include '/partials/pagination.tpl' %}
  {% include '/partials/breadcrumbs.tpl' %}
{% else %}
  <h2 class="
    list-error w-full m-4 text-center text-[3em] font-bold
    text-slate-400 lg:text-[5em]
  ">
    <i class="fa-solid fa-screwdriver-wrench m-0 my-3 mt-[0.3em] fa-2x"></i>
    <br />
    {% if content.title.plural is defined %}
      {{ content.title.plural ~ ' ' ~
         locale.backend.content.errors.not_configured }}
    {% else %}
      {{ locale.backend.content.errors.app_not_configured }}
    {% endif %}
  <h2>
{% endif %}
