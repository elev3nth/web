
{% if content.columns is not empty %}
  <div class="
    lg:flex p-3 bg-white
    rounded-tr-[0.3em] rounded-tl-[0.3em]
  ">
    {% for ckey, citem in content.columns %}
      {% if citem.type != 'key' and citem.type != 'uuid' %}
        <div class="
          list-header font-bold sticky top-0
        ">
          {{ citem.name|title }}
        </div>
      {% endif %}
    {% endfor %}
  </div>
  {% if content.data is not empty %}
  {% else %}
    <h2 class="
      list-error w-full m-4 text-center text-[3em] font-bold
      text-red-400 lg:text-[5em]
    ">
      <i class="fa-solid fa-circle-exclamation m-0 my-3 mt-[0.3em] fa-2x"></i>
      <br />
      No {{ content.title.plural }} Found
    <h2>
  {% endif %}
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
