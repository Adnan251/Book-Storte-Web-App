var SPApp = {
  handleSectionVisibility : function(spotlight_element){
      /**
       * All the elements which do not have to be in spotlight are hidden,
       * only the active section is visible
       */
      elements = ["#view_books", "#view_orders", "#view_purchases","#view_search_books","#view_search_by_writers"];
      $(elements.join(", ")).attr('hidden', true);
      
      $(spotlight_element).attr('hidden',false);
      $(spotlight_element).html("");
  }
}