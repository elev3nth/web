{% if content.columns is not empty %}
  {% include '/partials/breadcrumbs.tpl' %}
  <br />
  {% for ckey, citem in content.columns %}
    {% if content.data[citem.flds] is defined and
       citem.title|default(false) == true and 'D' in citem.crud %}
    <div class="delete-msg w-full m-4 text-center text-[3em] font-bold
      text-red-400 lg:text-[4em]
    ">
      <i class="fa-solid fa-circle-exclamation m-0 my-3 mt-[0.3em] fa-2x"></i>
      <h1>Are you sure to delete {{ content.data[citem.flds] }}?</h1>
      <button type="button" id="gobackBtn" name="gobackBtn" class="
      border border-solid border-black rounded-md text-slate-400
      font-bold p-2 px-5 h-14 bg-gradient-to-r from-slate-100 to-slate-300
      text-lg mx-5"
      onclick="history.back()">Go Back</button>
      <button type="submit" id="deleteBtn" name="deleteBtn" class="
      border border-solid border-black rounded-md text-white
      font-bold p-2 px-5 h-14 bg-gradient-to-r from-slate-600 to-black
      text-lg mx-5">Continue</button>
    </div>
    {% endif %}
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
