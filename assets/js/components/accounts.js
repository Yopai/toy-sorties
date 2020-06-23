import React from "react";

class AccountForm extends React.Component {
    constructor(props) {
        super(props);
        this.state = {value: ''};

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange(event) {
        this.setState({value: event.target.value});
    }

    handleSubmit(event) {
        alert('Le nom a été soumis : ' + this.state.value);
        event.preventDefault();
    }

    render() {
        return (
            <form onSubmit={this.handleSubmit}>
                <label>
                    Nom :
                    <input type="text" value={this.state.value} onChange={this.handleChange}/>
                </label>
                <input type="submit" value="Envoyer"/>
            </form>
        );
    }
}

class Accounts extends React.Component {
    constructor() {
        super();

        this.state = {
            available_sources: null,
        };

        this.handleInputChange = this.handleInputChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() {
        fetch('/api/sources')
            .then((response) => {
                if (!response.ok) {
                    throw Error(response.statusText);
                }
                return response.json();
            })
            .then(available_sources => {
                this.setState({available_sources});
                available_sources.map(source => {
                    this.setState({['source_'+source.id]: (this.props.data.indexOf(source.id) !== -1)});
                });
                this.setState({error: null});
            })
            .catch((error) => {
                this.setState({error});
            });
    }

    handleInputChange(event) {
        const target = event.target;

        this.setState({
            [target.id]: target.checked
        });
    }

    handleSubmit(event) {
        let sources = [];
        this.state.available_sources.map(source => {
            if (this.state['source_'+source.id]) {
                sources.push(source.id);
            }
        });
        console.log('submit : ');
        console.log(sources);
        this.props.setData(sources);
    }

    render() {
        if (this.state.error) {
            return this.state.error;
        }

        if (this.state.available_sources === null) {
            return (<p>Chargement...</p>);
        }

        return (
            <form onSubmit={this.handleSubmit}>
                {this.state.available_sources.map((source) => (
                        <div className="form-check" key={source.id}>
                            <input type="checkbox" className="form-check-input" id={'source_' + source.id}
                                   checked={(this.state['source_'+source.id]) ? 'checked' : ''}
                                   onChange={this.handleInputChange}/>
                            <label className="form-check-label" htmlFor={'source_' + source.id}>{source.name}</label>
                        </div>
                    )
                )}
                <button type=" button" className=" btn btn-primary">Valider</button>
            </form>
        )
    }
}

export default Accounts;
