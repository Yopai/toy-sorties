import React from 'react';
import ReactDOM from "react-dom";
import { NavLink } from "react-router-dom";


class MainMenu extends React.Component {
    render() {
        return (
            <div className="main-menu-container">
                <div className="menu list-group">
                    {this.props.actions.map((action) => (
                            <div key={action.name} className="menu-item list-item">
                                <NavLink to={action.navto}><i className={'fa fa-fw ' + action.icon}/>{action.name}</NavLink>
                            </div>
                        )
                    )}
                </div>
            </div>
        );
    }
}

class TogglingMainMenu extends React.Component {
    render() {
        return (
            <nav id="main-menu" role="navigation">
                <div id="menuToggle">
                    <label htmlFor="menu-toggler"><i className="fa fa-fw fa-bars fa-2x"/></label>
                    <MainMenu actions={this.props.actions}/>
                </div>
            </nav>
        );
    }
}

export default TogglingMainMenu
