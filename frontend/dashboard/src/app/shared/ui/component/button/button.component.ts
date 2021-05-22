import {Component, OnInit, Output, EventEmitter, Input} from '@angular/core';

@Component({
  selector: 'yes-button',
  templateUrl: './button.component.html',
  styleUrls: ['./button.component.scss']
})
export class ButtonComponent implements OnInit {
  @Output()
  onClick = new EventEmitter<any>();

  @Input()
  color = 'blue';

  @Input()
  label = 'Button';

  @Input()
  type: 'flat' | 'outline' | 'text' = 'flat';

  @Input()
  size: 'small' | 'medium' | 'large' | 'x-large' = 'medium';

  constructor() {
  }

  ngOnInit(): void {
  }

  public get classes(): string[] {
    return ['button', `button--${this.size} button--${this.type}`];
  }

  public get styles(): any {
    if (this.type === 'flat') {
      return {
        'background-color': this.color,
        color: 'white'
      };
    } else {
      return {
        'background-color': 'white',
        color: this.color
      };
    }
  }
}
