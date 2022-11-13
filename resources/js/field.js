import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-slack-channel-field', IndexField)
  app.component('detail-slack-channel-field', DetailField)
  app.component('form-slack-channel-field', FormField)
})
