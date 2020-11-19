'use strict';

class Board extends React.Component {
    render() {
        var className = 'board px-4';
        if (this.props.selected) {
            className += ' selected';
        }
        return <div className={className} onClick={(e) => {this.props.handleClick(e,this.props.index)}}>{this.props.index + 1}</div>;
    }
}

class BoardSwitcher extends React.Component {
    constructor() {
        super();
        this.state = {
            clickedIndex : 4,
        };
    }

    onButton(e, index) {
        e.preventDefault();
        this.setState({
            clickedIndex : index,
        });
    }

    render() {
        var boards = [];

        for (var ii = 0; ii < this.props.numBoards; ii++) {
            var isSelected = ii == this.state.clickedIndex;
            boards.push(<Board index={ii} key={ii} selected={isSelected} handleClick={this.onButton.bind(this)}/>);
        }

        return (
                <div className="boards d-flex justify-content-between mx-auto">{boards}</div>
        );
    }
}


const domContainer = document.querySelector('#react-dom');
ReactDOM.render(<BoardSwitcher numBoards={5} />, domContainer);