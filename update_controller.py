# Update Dashboard  
  
with open('app/Http/Controllers/Admin/DashboardController.php', 'r') as f:  
    controller_content = f.read()  
  
search = \"        // Get academic performance\"  
replace_text = \"        // School Population\n        $schoolPopulationData = [];\"  
  
controller_content = controller_content.replace(search, search + replace_text)  
  
with open('app/Http/Controllers/Admin/DashboardController.php', 'w') as f:  
    f.write(controller_content)  
  
print('Done')  
