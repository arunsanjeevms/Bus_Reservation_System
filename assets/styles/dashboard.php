<style>
    #main-content h3{
        padding-left: 1rem;
        margin-bottom: 0;
        text-transform: uppercase;
    }

    #status, #user, #admin{
        display: flex;
        flex-wrap: wrap;
    }

    .info-box{
        padding: 0.3rem 1rem;
        border-left: 4px solid;
        margin: 1rem;
        flex-basis: 45%;
        border-radius: 5px;
        box-shadow: 7px 7px 4px rgba(0, 0, 0, 0.25);
        background-color: white;
    }

    .heading{
        display: flex;
        justify-content: space-between;
    }
    .heading h5{
        color: white;
        text-align: center;
        padding: 0.5rem 1rem;
        flex-basis: 70%;
        border-radius: 0px;
        margin: 0.5rem 0;
    }

    .info-box p{
        margin: 0;
    }

    .info-content{
        margin-bottom: 1rem;
    }

    .info-content .num{
        font-size: 1.5rem;
    }

    .info-box a{
        display: block;
        text-align: right;
        text-decoration: none;
        font-weight: bold;
    }
    /* START-hardcoding */
    #Booking{
        border-color: #3e93d9;
    }

    #Booking h5{
        background-color: #3e93d9;
    }
    #Booking a{
        color: #3e93d9;
    }

    #Earning {
        border-color: #23bf29;
    }

    #Earning a{
        color: #23bf29;
    }

    #Bus {
        border-color: #009688;
    }

    #Bus h5{
        background-color: #009688;
    }

    #Bus a{
        color: #009688;
    }

    #Route{
        border-color: #f44336;
    }

    #Route h5{
        background-color: #f44336;
    }

    #Route a{
        color: #f44336;
    }

    #Seat{
        border-color: #A66314;
    }

    #Seat h5{
        background-color: #A66314;
    }

    #Seat a{
        color: #A66314;
    }

    #Customer{
        border-color: #2D2B28;
    }

    #Customer h5{
        background-color: #2D2B28;
    }

    #Customer a{
        color: #2D2B28;
    }

    #Admin{
        border-color: #607d8b;
    }

    #Admin h5{
        background-color: #607d8b;
    }

    #Earning h5{
        background-color: #23bf29;
    }

    #Admin a{
        color: #607d8b;
    }
    /* END-hardcoding */


    #admin .info-box{
        text-align: center;
        padding: 1rem 0;
        border: none;
    }

    #admin h4{
        margin: 0.5rem 0;
    }

    #admin img{
        border-radius: 50%;
    }


    @media only screen and (min-width:1000px){
        #main-content{
            flex-grow: 1;
        }

        .info-box{
            flex-basis: 20%;
        }

        #admin .info-box{
            flex-basis: 15%;
        }
    }
</style>