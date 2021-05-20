import { Component, Input, OnInit } from '@angular/core';
import { FormControl } from '@angular/forms';

@Component({
  selector: 'app-input',
  templateUrl: './input.component.html',
  styleUrls: ['./input.component.scss']
})
export class InputComponent implements OnInit {
  @Input() control = new FormControl('');
  @Input() containerClassName = '';
  @Input() icon = 'Home';
  @Input() iconSize = 16;
  @Input() placeholder = '';
  @Input() label = '';
  @Input() enableClearButton = false;

  get iconContainerWidth(): number {
    return this.iconSize * 2;
  }

  get isShowClearButton(): boolean {
    return this.enableClearButton && this.control?.value;
  }

  constructor() { }

  ngOnInit(): void {
    this.control = this.control ?? new FormControl('');
  }

  clear(): void {
    this.control.patchValue('');
  }
}
