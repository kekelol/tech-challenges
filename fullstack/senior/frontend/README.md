To launch project, please go to "fullstack/senior/frontend", and type:
```bash
yarn
yarn start
#This will open your browser to http://localhost:3000
```

You should see something like this:

![React App](https://raw.githubusercontent.com/kekelol/tech-challenges/master/fullstack/senior/frontend/App%20Front.png)

The app is made fully with ReactJS and interacts with a PHP endpoint at "http://localhost:8080/XX2".
The endpoint sends back a json with aggregated answers from surveys.
For each question, the ReactJS app loads up a "widget" component in the page, and depending on the question type (qcm, numeric ...) shows the data in different ways.

I recommand the usage of Google Chrome since the I've been using the "fetch" API for AJAX requests.

To improve this, next steps would be to :
* Test with different JSON data (with more than 2 questions, with more than 6 answers to qcm questions).
* Refactor code and maybe rethink design (component management).
* Render 'qcm' widgets with Highcharts or another Chart library to have better visualizing than a simple ul / li.
* Apply more modern css to the page and individual widgets.
* Eventually implement search (in left sidebar) to load from another agency.
