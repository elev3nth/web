{% if content.columns is not empty %}
  {% include '/partials/breadcrumbs.tpl' %}
  <br />
  {% for ckey, citem in content.columns %}
    <div class="lg:flex">
      <div class="
        text-right align-middle py-[0.4em] my-1
        lg:flex-none lg:w-[100px]
      ">
        {{ citem.name|title }}
      </div>
      <div class="text-left mx-3 my-1 lg:flex-1">
        {% include [
          '/forms/'~citem.type~'.tpl',
          '/forms/text.tpl'
        ] %}
      </div>
    </div>
  {% endfor %}
  <br />
  {% set hidebc = true %}
  {% include '/partials/breadcrumbs.tpl' %}
{% else %}
  <h2 class="
    list-error w-full m-4 text-center text-[3em] font-bold
    text-slate-400 lg:text-[5em]
  ">
    <i class="fa-solid fa-screwdriver-wrench m-0 my-3 mt-[0.3em] fa-2x"></i>
    <br />
    {{ content.title.plural }} Application Is Not Configured
  <h2>
{% endif %}
