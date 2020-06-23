import React from 'react';
import TogglingMainMenu from "./main_menu";
import Accounts from "./accounts";
import ReactDOM from "react-dom";
import {
  Route,
  HashRouter
} from "react-router-dom";
import OutingsList from "./outings_list";



class App extends React.Component {
    constructor() {
        super();

        this.state = {
            locale : 'fr-FR',
            sources: [1, 2, 3, 4],
        };

        this.actions = [
            {name: 'Mes comptes', icon: 'fa-user', navto: '/accounts', disabled: true},
            {name: 'Prochaines sorties', icon: 'fa-tree', navto: '/outings'},
            {name: 'Rechercher', icon: 'fa-search', navto: '/search', disabled: true},
            {name: 'Paramètres', icon: 'fa-', navto: '/settings', disabled: true},
        ];
    }

    setSources(sources) {
        this.setState({sources});
    }

    render() {
        return (
            <HashRouter>
                <TogglingMainMenu actions={this.actions}/>
                <div id="main-container" className="container-fluid">
                    <Route path="/outings" component={props => <OutingsList sources={this.state.sources.join(',')}/>} />
                    <Route path="/accounts" component={props => <Accounts data={this.state.sources} setData={this.setSources.bind(this)}/>} />
                </div>
            </HashRouter>
        );
    }
}

export default App;
