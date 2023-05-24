import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-rowstyle', IndexField)
  app.component('detail-rowstyle', DetailField)
  app.component('form-rowstyle', FormField)
})
